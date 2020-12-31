<?php

declare(strict_types=1);

namespace Caroler;

use Caroler\Commands\About;
use Caroler\Commands\Help;
use Caroler\Exceptions\ConfigurationException;
use Caroler\EventHandlers\EventHandlerFactory;
use Caroler\OutputWriters\DiscordOutputWriter;
use Caroler\OutputWriters\OutputWriterFactory;
use Caroler\OutputWriters\OutputWriterInterface;
use Caroler\Resources\Guild as GuildResource;
use Caroler\Resources\User as UserResource;
use Caroler\Stores\CommandStore;
use Caroler\Stores\ConfigStore;
use Caroler\Stores\RateLimitBucketStore;
use Exception;
use GuzzleHttp\Client;
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
     * @var string Caroler application version
     */
    public const APP_VERSION = '0.1.0-alpha';

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
    public const DISCORD_API_URL = 'https://discord.com/api/';

    /**
     * @var string Discord content delivery network URL
     */
    public const DISCORD_CDN_URL = 'https://cdn.discordapp.com/';

    /**
     * @var string Discord content delivery network endpoints
     * @todo Find a better solution.
     */
    public const DISCORD_CDN_ENDPOINTS = [
        'guild_icons' => 'icons/',
        'user_avatars' => 'avatars/',
    ];

    /**
     * @var \Caroler\Stores\ConfigStore Available configuration options
     */
    private $config;

    /**
     * @var \Caroler\OutputWriters\OutputWriterInterface[] System output writers
     * @todo Refactor to Monolog logging.
     */
    private $outputWriters = [];

    /**
     * @var \GuzzleHttp\Client Application HTTP client
     */
    private $httpClient;

    /**
     * @var \Caroler\Stores\RateLimitBucketStore Discord REST API rate limit tracker
     */
    private $rateLimitBuckets;

    /**
     * @var \Caroler\Stores\CommandStore Available bot commands
     */
    private $commands;

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
     * @todo Refactor to custom TimerInterface implementation.
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
     * @var GuildResource Reusable guild resource
     */
    private $guildResource;

    /**
     * @var GuildResource Reusable user resource
     */
    private $userResource;

    /**
     * @param array $config
     *
     * @throws \Caroler\Exceptions\ConfigurationException
     * @throws \GuzzleHttp\Exception\InvalidArgumentException
     */
    public function __construct(array $config = [])
    {
        $this->config = new ConfigStore();
        $this->configureMultiple($config);

        if (!$this->configExists('token')) {
            throw new ConfigurationException(
                'Discord bot token not defined.',
                ['config' => $config]
            );
        }
        $this->configExists('command_prefix')
            ?: $this->configure('command_prefix', '!');
        $this->configExists('debug')
            ?: $this->configure('debug', false);

        if (
            $this->configExists('system_channel')
            && $this->getConfig('system_channel') !== ''
        ) {
            $this->setOutputWriter(new DiscordOutputWriter(
                $this->getConfig('system_channel'),
                $this
            ));
        }

        $this->httpClient = new Client([
            'base_uri' => self::DISCORD_API_URL,
            'headers' => [
                'Authorization' => 'Bot ' . $this->getConfig('token')
            ]
        ]);

        $this->rateLimitBuckets = new RateLimitBucketStore();

        $this->commands = new CommandStore();
        $this->registerCommands($this->getConfig('commands'))
            ->registerCommands([
                About::class,
                Help::class,
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

        $connector(self::DISCORD_GATEWAY_URL . '?v=' . self::DISCORD_GATEWAY_VERSION . '&encoding=json')
            ->then(function (WebSocket $conn) {
                    $this->connection = $conn;
                    $this->connection->on('message', function (MessageInterface $payload) {
                        $payload = json_decode($payload->getPayload(), true);
                        $this->sequence = $payload['s'];

                        EventHandlerFactory::make($payload)->handle($this);
                    });
            },
            function (Exception $e) {
                $this->write("Failed to connect!");
                $this->write($e->getMessage(), true);

                $this->eventLoop->stop();
                $this->sing();
            });

        $this->eventLoop->run();
    }

    /**
     * Closes the Gateway connection and stops the application event loop.
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function conclude(): Caroler
    {
        $this->connection->close(1006);
        $this->eventLoop->stop();

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
     * @api
     */
    public function configExists(string $option): bool
    {
        return $this->config->exists($option);
    }

    /**
     * Retrieves a configuration option.
     *
     * @param string $option
     *
     * @return mixed
     * @api
     */
    public function getConfig(string $option)
    {
        return $this->config->get($option);
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
    public function configure(string $option, $value): Caroler
    {
        $this->config->put($option, $value);

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
    public function configureMultiple(array $options): Caroler
    {
        foreach ($options as $option => $value) {
            !is_string($option) && !is_string($value)
                ?: $this->configure($option, $value);
        }

        return $this;
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
     * @api
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
     * @uses \Caroler\Caroler::setOutputWriter()
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
     * @uses \Caroler\Caroler::setOutputWriters()
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
     */
    public function write($messages, bool $debug = false, string $type = null): Caroler
    {
        foreach ($this->outputWriters as $outputWriter) {
            if (!$debug || ($this->getConfig('debug'))) {
                $outputWriter->write($messages, $type);
            }
        }

        return $this;
    }

    // =========================================================================
    // Rate Limit Bucket
    // =========================================================================

    /**
     * Determines whether or not a Rate Limit Bucket exists.
     *
     * @param string $bucket
     *
     * @return bool
     */
    public function rateLimitBucketExists(string $bucket): bool
    {
        return $this->rateLimitBuckets->exists($bucket);
    }

    /**
     * Retrieves a Rate Limit Bucket by one of its included endpoints.
     *
     * @param string $route
     *
     * @return array|null
     */
    public function getRateLimitBucketByRoute(string $route): ?array
    {
        $buckets = $this->rateLimitBuckets->get();
        foreach ($buckets as $bucket) {
            foreach ($bucket['endpoints'] as $bucketEndpoint) {
                if ($route === $bucketEndpoint) {
                    return $bucket;
                }
            }
        }

        return null;
    }

    /**
     * Stores or updates a Rate Limit Bucket.
     *
     * @param string $bucket
     * @param string $endpoint
     * @param int|null $remaining
     * @param int|null $reset
     *
     * @return \Caroler\Caroler
     */
    public function updateRateLimitBucket(
        string $bucket,
        string $endpoint,
        ?int $remaining = null,
        int $reset = null
    ): Caroler {
        $rateLimitBucket = [];

        if ($this->rateLimitBuckets->exists($bucket)) {
            $rateLimitBucket = $this->rateLimitBuckets->get($bucket);
            !isset($rateLimitBucket['endpoints'][$endpoint])
                ?: $rateLimitBucket['endpoints'][] = $endpoint;
        } else {
            $rateLimitBucket['endpoints'] = [$endpoint];
        }

        $rateLimitBucket['remaining'] = $remaining;
        $rateLimitBucket['reset'] = $reset;

        $this->rateLimitBuckets->put($bucket, $rateLimitBucket);

        return $this;
    }

    // =========================================================================
    // Commands
    // =========================================================================

    /**
     * Determines whether or not a command exists.
     *
     * @param string $name
     *
     * @return bool
     * @api
     */
    public function commandExists(string $name): bool
    {
        return $this->commands->exists($name);
    }

    /**
     * Retrieves a command class' name.
     *
     * @param string $name
     *
     * @return string|null
     * @api
     */
    public function getCommand(string $name): ?string
    {
        return $this->commands->get($name);
    }

    /**
     * Retrieves all available commands.
     *
     * @return array
     * @api
     */
    public function getCommands(): array
    {
        return $this->commands->get();
    }

    /**
     * Registers a bot command.
     *
     * @param string $class
     * @param null $name
     *
     * @return \Caroler\Caroler
     * @api
     */
    public function registerCommand(string $class, $name = null): Caroler
    {
        /** @var \Caroler\Commands\CommandInterface $command */
        $command = new $class();
        $name = !is_null($name) && is_string($name)
            ? $name : $command->getName();
        unset($command);

        $this->commands->put($name, $class);

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
        foreach ($commands as $name => $command) {
            $this->registerCommand($command, $name);
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
     * @uses \Caroler\Caroler::registerCommand()
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
     * @uses \Caroler\Caroler::registerCommands()
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

    // =========================================================================
    // Resource Accessors
    // =========================================================================

    /**
     * @param string $guildId
     *
     * @return \Caroler\Resources\Guild
     * @api
     */
    public function guild(string $guildId): GuildResource
    {
        isset($this->guildResource) ?: $this->guildResource = new GuildResource();

        $this->guildResource->prepare($guildId, $this);

        return $this->guildResource;
    }

    /**
     * @param string|null $userId
     *
     * @return \Caroler\Resources\User
     * @api
     */
    public function user(string $userId = null): UserResource
    {
        !is_null($userId) ?: $userId = '@me';
        isset($this->userResource) ?: $this->userResource = new UserResource();

        $this->userResource->prepare($userId, $this);

        return $this->userResource;
    }
}
