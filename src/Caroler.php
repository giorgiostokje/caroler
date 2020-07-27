<?php

declare(strict_types=1);

namespace Caroler;

use Caroler\Commands\About;
use Caroler\Commands\Help;
use Caroler\Exceptions\TokenNotFoundException;
use Caroler\Objects\Embed;
use Caroler\OutputWriters\DiscordOutputWriter;
use Caroler\OutputWriters\OutputWriterInterface;
use Caroler\Stores\CommandStore;
use Caroler\Stores\ConfigStore;
use Exception;
use Caroler\Factories\EventHandlerFactory;
use Caroler\Objects\Message;
use Caroler\OutputWriters\OutputWriterFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\Timer;
use React\Socket\Connector as ReactConnector;

/**
 * Application entry point
 *
 * @package Caroler
 */
class Caroler
{
    /**
     * @var string Discord WebSocket Gateway URL
     */
    private const DISCORD_GATEWAY_URL = 'wss://gateway.discord.gg/';

    /**
     * @var int Discord WebSocket Gateway version
     * @see https://discord.com/developers/docs/topics/gateway#gateways-gateway-versions
     */
    private const DISCORD_GATEWAY_VERSION = 6;

    /**
     * @var string Discord REST API URL
     */
    private const DISCORD_API_URL = 'https://discord.com/api/';

    /**
     * @var \Caroler\Stores\ConfigStore Available configuration options
     */
    private $configStore;

    /**
     * @var \Caroler\OutputWriters\OutputWriterInterface[] System output writers
     */
    private $outputWriters = [];

    /**
     * @var \GuzzleHttp\Client Application HTTP client
     */
    private $httpClient;

    /**
     * @var \Caroler\Stores\CommandStore Available bot commands
     */
    private $commandStore;

    /**
     * @var \React\EventLoop\LoopInterface Application event loop
     */
    private $eventLoop;

    /**
     * @var \Ratchet\Client\WebSocket Application WebSocket connection
     */
    private $connection;

    /**
     * @var \React\EventLoop\Timer\Timer Heartbeat Timer instance
     */
    private $heartbeatTimer = null;

    /**
     * @var int Whether or not the previous heartbeat was acknowledged.
     */
    private $heartbeatAcknowledged = false;

    /**
     * @var int|null Last received sequence number
     */
    private $sequence;

    /**
     * @var \Caroler\State Current state of the application
     */
    private $state;

    /**
     * @param array $options
     *
     * @throws \Caroler\Exceptions\TokenNotFoundException
     */
    public function __construct(array $options = [])
    {
        $this->configStore = new ConfigStore();
        $this->configureOptions($options);
        if (!$this->optionExists('token')) {
            throw new TokenNotFoundException("No Discord Bot Token defined!");
        }
        $this->optionExists('command_prefix') ?: $this->configureOption('command_prefix', '!');
        $this->optionExists('debug') ?: $this->configureOption('debug', false);

        if ($this->optionExists('system_channel') && $this->getOption('system_channel') !== '') {
            $this->setOutputWriter(new DiscordOutputWriter($this->getOption('system_channel'), $this));
        }

        $this->httpClient = new Client([
            'base_uri' => self::DISCORD_API_URL,
            'headers' => [
                'Authorization' => 'Bot ' . $this->getOption('token')
            ]
        ]);

        $this->commandStore = new CommandStore();
        $this->registerCommands($this->getOption('commands'))->registerCommands([
            Help::class,
            About::class,
        ]);
    }

    /**
     * Initializes the connection and listens for events from the Discord Gateway.
     *
     * @return void
     * @api
     * @see https://discord.com/developers/docs/topics/opcodes-and-status-codes
     */
    public function sing(): void
    {
        !empty($this->outputWriters) ?: $this->setOutputWriter('console');

        $this->eventLoop = Factory::create();
        $rConnector = new ReactConnector($this->eventLoop);
        $connector = new Connector($this->eventLoop, $rConnector);

        $this->write("Connecting to the Discord Gateway...");

        $connector(self::DISCORD_GATEWAY_URL . '?v=' . self::DISCORD_GATEWAY_VERSION . '&encoding=json')->then(
            function (WebSocket $conn) {
                $this->connection = $conn;
                $this->connection->on('message', function (MessageInterface $payload) {
                    $payload = json_decode($payload->getPayload());
                    $this->sequence = $payload->s;

                    EventHandlerFactory::make($payload)->handle($this);
                });
            },
            function (Exception $e) {
                $this->write("Failed to connect!");
                $this->write($e->getMessage(), true);

                $this->eventLoop->stop();
            }
        );

        $this->eventLoop->run();
    }

    /**
     * Sends a message to a channel.
     *
     * @param string $message
     * @param \Caroler\Objects\Message|string $context Message object or channel id
     * @param \Caroler\Objects\Embed|null $embed
     *
     * @return $this
     */
    public function send(string $message, $context, Embed $embed = null): Caroler
    {
        if (!$context instanceof Message && !is_string($context)) {
            throw new \InvalidArgumentException("Context must be either a Message object or a string!");
        }

        $channelId = $context instanceof Message ? $context->channelId : $context;
        $data = ['json' => ['content' => $message]];
        !isset($embed) ?: $data['json']['embed'] = $embed->toArray();

        try {
            $this->httpClient->post(
                "channels/" . $channelId . "/messages",
                $data
            );
        } catch (RequestException $e) {
            $this->write("Failed to send message: " . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $this->write("Discord responded with: " . Psr7\str($e->getResponse()));
            }
        }

        return $this;
    }

    /**
     * Closes the Gateway connection and stops the application event loop.
     *
     * @return \Caroler\Caroler
     */
    public function conclude(): Caroler
    {
        $this->connection->close(1006);
        $this->eventLoop->stop();

        return $this;
    }

    /**
     * @return \React\EventLoop\LoopInterface
     */
    public function getEventLoop(): LoopInterface
    {
        return $this->eventLoop;
    }

    /**
     * @return \Ratchet\Client\WebSocket
     */
    public function getConnection(): WebSocket
    {
        return $this->connection;
    }

    /**
     * @return \React\EventLoop\Timer\Timer|null
     */
    public function getHeartbeatTimer(): ?Timer
    {
        return $this->heartbeatTimer;
    }

    /**
     * @param \React\EventLoop\Timer\Timer|null $timer
     *
     * @return \Caroler\Caroler
     */
    public function setHeartbeatTimer(?Timer $timer): Caroler
    {
        $this->heartbeatTimer = $timer;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHeartbeatAcknowledged(): bool
    {
        return $this->heartbeatAcknowledged;
    }

    /**
     * @param bool $acknowledged
     *
     * @return $this
     */
    public function setHeartbeatAcknowledged(bool $acknowledged): Caroler
    {
        $this->heartbeatAcknowledged = $acknowledged;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    // =========================================================================
    // Config
    // =========================================================================

    /**
     * Determines whether or not an option was configured.
     *
     * @param string $option
     *
     * @return bool
     */
    public function optionExists(string $option): bool
    {
        return $this->configStore->exists($option);
    }

    /**
     * Retrieves a configuration option.
     *
     * @param string $option
     *
     * @return mixed
     */
    public function getOption(string $option)
    {
        return $this->configStore->get($option);
    }

    /**
     * Configures an option.
     *
     * @param string $option
     * @param mixed $value
     *
     * @return $this
     * @api
     */
    public function configureOption(string $option, $value): Caroler
    {
        $this->configStore->set($option, $value);

        return $this;
    }

    /**
     * Configures multiple options.
     *
     * @param array $options
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function configureOptions(array $options): Caroler
    {
        foreach ($options as $option => $value) {
            !is_string($option) && !is_string($value) ?: $this->configureOption($option, $value);
        }

        return $this;
    }

    /**
     * Configures an option.
     *
     * @param string $option
     * @param mixed $value
     *
     * @return $this
     * @api
     */
    public function option(string $option, $value): Caroler
    {
        return $this->configureOption($option, $value);
    }

    /**
     * Configures multiple options.
     *
     * @param array $options
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function options(array $options): Caroler
    {
        return $this->configureOptions($options);
    }

    // =========================================================================
    // Output Writers
    // =========================================================================

    /**
     * Gets the output writers.
     *
     * @return \Caroler\OutputWriters\OutputWriterInterface[]
     * @api
     */
    public function getOutputWriters(): array
    {
        return $this->outputWriters;
    }

    /**
     * Sets an output writer.
     *
     * @param string|OutputWriterInterface|\Symfony\Component\Console\Output\OutputInterface $outputWriter
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function setOutputWriter($outputWriter): Caroler
    {
        $this->outputWriters[] = OutputWriterFactory::make($outputWriter);

        return $this;
    }

    /**
     * Sets multiple output writers.
     *
     * @param array[] $outputWriters
     *
     * @return \Caroler\Caroler
     */
    public function setOutputWriters(array $outputWriters): Caroler
    {
        /** @var string|OutputWriterInterface|\Symfony\Component\Console\Output\OutputInterface $outputWriter */
        foreach ($outputWriters as $outputWriter) {
            $this->setOutputWriter($outputWriter);
        }

        return $this;
    }

    /**
     * Sets an output writer.
     *
     * @param string|OutputWriterInterface|\Symfony\Component\Console\Output\OutputInterface $outputWriter
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function outputWriter($outputWriter): Caroler
    {
        return $this->setOutputWriter($outputWriter);
    }

    /**
     * Sets multiple output writers.
     *
     * @param array[] $outputWriters
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function outputWriters(array $outputWriters): Caroler
    {
        return $this->setOutputWriters($outputWriters);
    }

    /**
     * Writes one or more messages to the output writer.
     *
     * @param string|string[] $messages
     * @param bool $debug
     * @param string|null $type info|comment|question|error
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function write($messages, bool $debug = false, string $type = null): Caroler
    {
        foreach ($this->outputWriters as $outputWriter) {
            if (!$debug || ($this->getOption('debug') && $debug)) {
                $outputWriter->write($messages, $type);
            }
        }

        return $this;
    }

    // =========================================================================
    // Commands
    // =========================================================================

    /**
     * Determines whether or not a command exists.
     *
     * @param string $signature
     *
     * @return bool
     */
    public function commandExists(string $signature): bool
    {
        return $this->commandStore->exists($signature);
    }

    /**
     * Retrieves a command class' name.
     *
     * @param string $signature
     *
     * @return string|null
     */
    public function getCommand(string $signature): ?string
    {
        return $this->commandStore->get($signature);
    }

    /**
     * Retrieves all available commands.
     *
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commandStore->get();
    }

    /**
     * Registers a bot command.
     *
     * @param string $class
     * @param string|null $signature
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function registerCommand(string $class, $signature = null): Caroler
    {
        try {
            /** @var \Caroler\Commands\CommandInterface $command */
            $command = new $class();
            $signature = !is_null($signature) && is_string($signature) ? $signature : $command->getSignature();
            unset($command);
        } catch (Exception $e) {
            throw new \InvalidArgumentException("Invalid command class \"$class\"!");
        }

        $this->commandStore->set($signature, $class);

        return $this;
    }

    /**
     * Registers multiple bot commands.
     *
     * @param array $commands As [<'signature' => >'command_class', ...]
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function registerCommands(array $commands): Caroler
    {
        foreach ($commands as $signature => $command) {
            $this->registerCommand($command, $signature);
        }

        return $this;
    }

    /**
     * Registers a bot command.
     *
     * @param string $class
     * @param string|null $signature
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function command(string $class, string $signature = null): Caroler
    {
        return $this->registerCommand($class, $signature);
    }

    /**
     * Registers multiple bot commands.
     *
     * @param array $commands As [<'signature' => >'command_class', ...]
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function commands(array $commands): Caroler
    {
        return $this->registerCommands($commands);
    }

    // =========================================================================
    // State
    // =========================================================================

    /**
     * @return \Caroler\State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param \Caroler\State $state
     *
     * @return \Caroler\Caroler
     */
    public function setState(State $state): Caroler
    {
        $this->state = $state;

        return $this;
    }
}
