<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Role object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/topics/permissions#role-object
 */
class Role extends AbstractObject implements ObjectInterface
{
    /**
     * @var string Role id
     */
    protected $id;

    /**
     * @var string Role name
     */
    protected $name;

    /**
     * @var int Integer representation of hexadecimal role color code
     */
    protected $color;

    /**
     * @var bool Whether the role is pinned in the user listing
     */
    protected $hoist;

    /**
     * @var int Role position
     */
    protected $position;

    /**
     * @var int Legacy role permission bit set
     */
    protected $permissions;

    /**
     * @var string Role permission bit set
     */
    protected $permissionsNew;

    /**
     * @var bool Whether the role is managed by an integration
     */
    protected $managed;

    /**
     * @var bool Whether the role is mentionable
     */
    protected $mentionable;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getColor(): int
    {
        return $this->color;
    }

    /**
     * @return bool
     */
    public function isHoist(): bool
    {
        return $this->hoist;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getPermissions(): int
    {
        return $this->permissions;
    }

    /**
     * @return string
     */
    public function getPermissionsNew(): string
    {
        return $this->permissionsNew;
    }

    /**
     * @return bool
     */
    public function isManaged(): bool
    {
        return $this->managed;
    }

    /**
     * @return bool
     */
    public function isMentionable(): bool
    {
        return $this->mentionable;
    }
}
