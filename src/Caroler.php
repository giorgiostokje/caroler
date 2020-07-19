<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler;

use Exception;
use GiorgioStokje\Caroler\Factories\EventHandlerFactory;
use GiorgioStokje\Caroler\Objects\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
 * @package GiorgioStokje\Caroler
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
     * @var \GuzzleHttp\Client Application HTTP client
     */
    private $httpClient;

    /**
     * @var string Discord bot token
     * @see https://discord.com/developers/applications/
     */
    private $token;

    /**
     * @var string Chat command prefix
     */
    private $commandPrefix;

    /**
     * @var array Available Commands
     */
    private $commands = [];

    /**
     * @var bool Debugging mode
     */
    private $debug;

    /**
     * @var \React\EventLoop\LoopInterface Application event loop
     */
    private $loop;

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
     * @var \GiorgioStokje\Caroler\State Current state of the application
     */
    private $state;

    /**
     * Constructs the client.
     *
     * @param string $token
     * @param array $options
     */
    public function __construct(string $token, array $options = [])
    {
        $this->httpClient = new Client([
            'base_uri' => self::DISCORD_API_URL,
            'headers' => [
                'Authorization' => 'Bot ' . $this->token = $token
            ]
        ]);

        $this->commandPrefix = $options['command_prefix'] ?? '!';
        $this->debug = $options['debug'] ?? false;

        $this->registerCommands();
    }

    /**
     * Register's the bot's commands.
     *
     * @return \GiorgioStokje\Caroler\Caroler
     */
    private function registerCommands(): Caroler
    {
        $this->write("Registering Commands...");

        foreach (scandir(dirname(__FILE__) . '/Commands') as $filename) {
            if (
                substr($filename, -4) === '.php'
                && $filename !== 'CommandInterface.php'
                && $filename !== 'AbstractCommand.php'
            ) {
                $class = __NAMESPACE__ . '\Commands\\' . str_replace('.php', '', $filename);
                /** @var \GiorgioStokje\Caroler\Commands\CommandInterface $command */
                $command = new $class();
                $this->commands[$command->getSignature()] = $class = get_class($command);

                $this->write("Registered \"{$command->getSignature()}\" Command from $class.", true);

                unset($command);
            }
        }

        $this->write(count($this->commands) . " Command(s) registered.");

        return $this;
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
        $this->loop = Factory::create();
        $rConnector = new ReactConnector($this->loop);
        $connector = new Connector($this->loop, $rConnector);

        $this->write("Connecting to the Discord Gateway...");

        $connector(self::DISCORD_GATEWAY_URL . '?v=' . self::DISCORD_GATEWAY_VERSION . '&encoding=json')->then(
            function (WebSocket $conn) {
                $this->connection = $conn;
                $this->connection->on('message', function (MessageInterface $payload) {
                    $this->write("Payload received from the Gateway:\n$payload", true);

                    $payload = json_decode($payload->getPayload());
                    $this->sequence = $payload->s;

                    EventHandlerFactory::make($payload)->handle($this);
                });
            },
            function (Exception $e) {
                $this->write("Failed to connect!");
                $this->write($e->getMessage(), true);

                $this->loop->stop();
            }
        );

        $this->loop->run();
    }

    /**
     * Closes the Gateway connection and stops the application event loop.
     *
     * @return \GiorgioStokje\Caroler\Caroler
     */
    public function conclude(): Caroler
    {
        $this->connection->close(1006);
        $this->loop->stop();

        return $this;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getCommandPrefix(): string
    {
        return $this->commandPrefix;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return \React\EventLoop\LoopInterface
     */
    public function getLoop(): LoopInterface
    {
        return $this->loop;
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
     * @return \GiorgioStokje\Caroler\Caroler
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

    /**
     * @return \GiorgioStokje\Caroler\State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param \GiorgioStokje\Caroler\State $state
     *
     * @return \GiorgioStokje\Caroler\Caroler
     */
    public function setState(State $state): Caroler
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Sends a message to a channel.
     *
     * @param string $msg
     * @param \GiorgioStokje\Caroler\Objects\Message $context
     *
     * @return $this
     */
    public function send(string $msg, Message $context): Caroler
    {
        try {
            $this->httpClient->post("channels/$context->channelId/messages", [
                'json' => [
                    'content' => $msg
                ]
            ]);
        } catch (GuzzleException $e) {
            $this->write($e->getMessage(), true);
        }

        return $this;
    }

    /**
     * Writes a message to the console.
     *
     * @param string $msg
     * @param bool $debug
     *
     * @return \GiorgioStokje\Caroler\Caroler
     */
    public function write(string $msg, bool $debug = false): Caroler
    {
        $prefix = $debug ? "DEBUG: " : "";

        !(($this->debug && $debug) || !$debug) ?: printf("[%s] %s%s\n", date("Y/m/d H:i:s"), $prefix, $msg);

        return $this;
    }
}
