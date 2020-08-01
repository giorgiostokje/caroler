<?php

declare(strict_types=1);

namespace Caroler\EventHandlers\DispatchEvents;

use Caroler\Caroler;
use Caroler\EventHandlers\AbstractEventHandler;
use Caroler\EventHandlers\EventHandlerInterface;
use Caroler\Objects\User;
use Caroler\State;
use stdClass;

/**
 * Ready Dispatch Event Handler class
 *
 * @package Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#ready
 */
class Ready extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @var int Gateway version
     */
    protected $v;

    /**
     * @var stdClass|\Caroler\Objects\User
     */
    protected $user;

    /**
     * @var array Empty array
     */
    protected $privateChannels;

    /**
     * @var array
     */
    protected $guilds;

    /**
     * @var string Used for resuming connections
     */
    protected $sessionId;

    /**
     * @var int[]|null Session shard information
     */
    protected $shard;

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $user = new User();
        $user->prepare($this->user);

        $caroler->setState((new State())->setV($this->v)
            ->setUser($user)
            ->setPrivateChannels($this->privateChannels)
            ->setGuilds($this->guilds)
            ->setSessionId($this->sessionId)
            ->setShard($this->shard));

        $caroler->write("Successfully connected!");

        return $this;
    }
}
