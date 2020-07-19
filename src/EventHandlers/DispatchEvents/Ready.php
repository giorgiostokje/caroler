<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers\DispatchEvents;

use GiorgioStokje\Caroler\Caroler;
use GiorgioStokje\Caroler\EventHandlers\AbstractEventHandler;
use GiorgioStokje\Caroler\EventHandlers\EventHandlerInterface;
use GiorgioStokje\Caroler\Objects\User;
use GiorgioStokje\Caroler\State;

/**
 * Ready Event handler class
 *
 * @package GiorgioStokje\Caroler\EventHandlers
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
