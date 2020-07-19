<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events\DispatchEvents;

use GiorgioStokje\Caroler\Caroler;
use GiorgioStokje\Caroler\Events\AbstractEvent;
use GiorgioStokje\Caroler\Events\EventInterface;
use GiorgioStokje\Caroler\Objects\User;
use GiorgioStokje\Caroler\State;

/**
 * Ready Event handler class
 *
 * @package GiorgioStokje\Caroler\Events
 * @see https://discord.com/developers/docs/topics/gateway#ready
 */
class Ready extends AbstractEvent implements EventInterface
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
    public function handle(Caroler $caroler): EventInterface
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
