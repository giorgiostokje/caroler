<?php

declare(strict_types=1);

namespace Caroler\Objects;

use Caroler\Resources\Guild as GuildResource;
use Caroler\Traits\HasPermissions;

/**
 * Guild Member object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/guild#guild-member-object
 */
class GuildMember extends AbstractObject implements ObjectInterface
{
    use HasPermissions;

    /**
     * @var \Caroler\Objects\User|null Guild member user representation
     */
    protected $user;

    /**
     * @var string|null User guild nickname
     */
    protected $nick;

    /**
     * @var string[] User guild role ids
     */
    protected $roles;

    /**
     * @var string ISO8601 timestamp user guild join date
     */
    protected $joinedAt;

    /**
     * @var string|null ISO8601 timestamp of when the user started boosting the guild
     */
    protected $premiumSince;

    /**
     * @var bool Whether the user is deafened in voice channels
     */
    protected $deaf;

    /**
     * @var bool Whether the user is muted in voice channels
     */
    protected $mute;

    /**
     * @inheritDoc
     */
    public function prepare($data): ObjectInterface
    {
        if (isset($data['user'])) {
            $this->user = (new User())->prepare($data['user']);
            unset($data['user']);
        }

        parent::prepare($data);

        return $this;
    }

    /**
     * @return \Caroler\Objects\User|null
     */
    public function getUser(): ?\Caroler\Objects\User
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getJoinedAt(): string
    {
        return $this->joinedAt;
    }

    /**
     * @return string|null
     */
    public function getPremiumSince(): ?string
    {
        return $this->premiumSince;
    }

    /**
     * @return bool
     */
    public function isDeaf(): bool
    {
        return $this->deaf;
    }

    /**
     * @return bool
     */
    public function isMute(): bool
    {
        return $this->mute;
    }
}
