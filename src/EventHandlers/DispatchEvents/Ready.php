<?php

declare(strict_types=1);

namespace Caroler\EventHandlers\DispatchEvents;

use Caroler\Caroler;
use Caroler\EventHandlers\AbstractEventHandler;
use Caroler\EventHandlers\EventHandlerInterface;
use Caroler\Objects\User;
use Caroler\State;

/**
 * Ready Event handler class
 *
 * @package Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#ready
 */
class Ready extends AbstractEventHandler implements EventHandlerInterface
{
    protected $v;
    protected $user;
    protected $privateChannels;
    protected $guilds;
    protected $sessionId;
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
