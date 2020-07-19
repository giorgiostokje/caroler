<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler;

use Exception;
use GiorgioStokje\Caroler\Objects\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
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
     * @var bool Debugging mode
     */
    private $debug;

    /**
     * @var array Available Commands
     */
    private $commands = [];

    /**
     * @var \React\EventLoop\LoopInterface Application event loop
     */
    private $loop;

    /**
     * @var \Ratchet\Client\WebSocket Application WebSocket connection
     */
    private $connection;

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

    public function getSequence(): ?int
    {
        return $this->sequence;
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
