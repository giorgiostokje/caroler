<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler;

use GiorgioStokje\Caroler\Objects\User;

/**
 * Represents the current state of the application.
 *
 * The state is populated from the received Ready event, after the initial handshake with the Gateway.
 *
 * @package GiorgioStokje\Caroler
 * @see https://discord.com/developers/docs/topics/gateway#ready
 */
class State
{
    /**
     * @var int Gateway version
     */
    private $v;

    /**
     * @var \GiorgioStokje\Caroler\Objects\User Information about the user
     */
    private $user;

    /**
     * @var array Empty arrays
     */
    private $privateChannels;

    /**
     * @var \GiorgioStokje\Caroler\Objects\Guild[] Guilds the bot is in
     */
    private $guilds;

    /**
     * @var string Used for resuming connections
     */
    private $sessionId;

    /**
     * @var int[] The shard information associated with this session, if sent when identifying
     */
    private $shard;

    /**
     * @return int
     */
    public function getV(): int
    {
        return $this->v;
    }

    /**
     * @param int $v
     *
     * @return \GiorgioStokje\Caroler\State
     */
    public function setV(int $v)
    {
        $this->v = $v;

        return $this;
    }

    /**
     * @return \GiorgioStokje\Caroler\Objects\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param \GiorgioStokje\Caroler\Objects\User $user
     *
     * @return \GiorgioStokje\Caroler\State
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function getPrivateChannels(): array
    {
        return $this->privateChannels;
    }

    /**
     * @param array $privateChannels
     *
     * @return \GiorgioStokje\Caroler\State
     */
    public function setPrivateChannels(array $privateChannels)
    {
        $this->privateChannels = $privateChannels;

        return $this;
    }

    /**
     * @return \GiorgioStokje\Caroler\Objects\Guild[]
     */
    public function getGuilds(): array
    {
        return $this->guilds;
    }

    /**
     * @param \GiorgioStokje\Caroler\Objects\Guild[] $guilds
     *
     * @return \GiorgioStokje\Caroler\State
     */
    public function setGuilds(array $guilds)
    {
        $this->guilds = $guilds;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return \GiorgioStokje\Caroler\State
     */
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return int[]|null
     */
    public function getShard(): ?array
    {
        return $this->shard;
    }

    /**
     * @param int[]|null $shard
     *
     * @return \GiorgioStokje\Caroler\State
     */
    public function setShard(?array $shard)
    {
        $this->shard = $shard;

        return $this;
    }
}
