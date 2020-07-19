<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler;

use Exception;
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
     * @var string Discord bot token
     * @see https://discord.com/developers/applications/
     */
    private $token;

    /**
     * @var bool Debugging mode
     */
    public $debug;

    /**
     * @var \React\EventLoop\LoopInterface Application event loop
     */
    private $loop;

    /**
     * @var \Ratchet\Client\WebSocket Application WebSocket connection
     */
    private $connection;

    /**
     * @var int Last received sequence number
     */
    public $sequence;

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
        $this->token = $token;
        $this->debug = $options['debug'] ?? false;
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
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return \React\EventLoop\LoopInterface
     */
    public function getLoop(): LoopInterface
    {
        return $this->loop;
    }

    public function getConnection(): WebSocket
    {
        return $this->connection;
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
     * Writes a message to the console.
     *
     * @param string $msg
     * @param bool $debug
     */
    public function write(string $msg, bool $debug = false)
    {
        $prefix = $debug ? "DEBUG: " : "";

        !(($this->debug && $debug) || !$debug) ?: printf("[%s] %s%s\n", date("Y/m/d H:i:s"), $prefix, $msg);
    }
}
