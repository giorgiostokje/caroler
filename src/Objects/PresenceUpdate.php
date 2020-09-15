<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Presence Update object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/topics/gateway#presence-update
 */
class PresenceUpdate extends AbstractObject implements ObjectInterface
{
    /**
     * @var \Caroler\Objects\User User presence being updated
     */
    protected $user;

    /**
     * @var string[] User roles
     */
    protected $roles;

    /**
     * @var \Caroler\Objects\Activity|null Current user activity
     */
    protected $game;

    /**
     * @var Guild id
     */
    protected $guildId;

    /**
     * @var string Either "idle", "dnd", "online" or "offline"
     */
    protected $status;

    /**
     * @var \Caroler\Objects\Activity[] Current user activities
     */
    protected $activities;

    /**
     * @var \Caroler\Objects\ClientStatus
     */
    protected $clientStatus;

    /**
     * @var string|null User guild boosting start ISO8601 timestamp
     */
    protected $premiumSince;

    /**
     * @var string|null User guild nickname
     */
    protected $nick;

    /**
     * @inheritDoc
     * @todo Populate Activity objects
     */
    public function prepare(array $data): ObjectInterface
    {
        if (isset($data['user'])) {
            $this->user = (new User())->prepare($data['user']);
            unset($data['user']);
        }

//        if (isset($data['game'])) {
//            $this->game = (new Activity())->prepare($data['game']);
//            unset($data['game']);
//        }

//        if (isset($data['activities'])) {
//            $this->activities = $this->transform($data['activities'], Activity::class);
//            unset($data['activities']);
//        }

        if (isset($data['client_status'])) {
            $this->clientStatus = (new ClientStatus())->prepare($data['client_status']);
            unset($data['client_status']);
        }

        parent::prepare($data);

        return $this;
    }

    /**
     * @return \Caroler\Objects\User
     */
    public function getUser(): \Caroler\Objects\User
    {
        return $this->user;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return \Caroler\Objects\Activity|null
     */
    public function getGame(): ?\Caroler\Objects\Activity
    {
        return $this->game;
    }

    /**
     * @return \Caroler\Objects\Guild
     */
    public function getGuildId(): \Caroler\Objects\Guild
    {
        return $this->guildId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \Caroler\Objects\Activity[]
     */
    public function getActivities(): array
    {
        return $this->activities;
    }

    /**
     * @return \Caroler\Objects\ClientStatus
     */
    public function getClientStatus(): \Caroler\Objects\ClientStatus
    {
        return $this->clientStatus;
    }

    /**
     * @return string|null
     */
    public function getPremiumSince(): ?string
    {
        return $this->premiumSince;
    }

    /**
     * @return string|null
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }
}
