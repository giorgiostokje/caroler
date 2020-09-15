<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Emoji object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/emoji#emoji-object
 */
class Emoji extends AbstractObject implements ObjectInterface
{
    /**
     * @var string|null Emoji id
     */
    protected $id;

    /**
     * @var string|null Emoji name
     */
    protected $name;

    /**
     * @var \Caroler\Objects\Role[]|null Emoji roles whitelist
     */
    protected $roles;

    /**
     * @var \Caroler\Objects\User|null Emoji creator
     */
    protected $user;

    /**
     * @var bool|null Whether the emoji must be wrapped in colons
     */
    protected $requireColons;

    /**
     * @var bool|null Whether the emoji is managed
     */
    protected $managed;

    /**
     * @var bool|null Whether the emoji is animated
     */
    protected $animated;

    /**
     * @var bool|null Whether the emoji can be used
     */
    protected $available;

    /**
     * @inheritDoc
     */
    public function prepare(array $data): ObjectInterface
    {
        if (isset($data['roles'])) {
            $this->roles = $this->transform($data['roles'], Role::class);
            unset($data['roles']);
        }

        if (isset($data['user'])) {
            $this->user = (new User())->prepare($data['user']);
            unset($data['user']);
        }

        parent::prepare($data);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return \Caroler\Objects\Role[]|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @return \Caroler\Objects\User|null
     */
    public function getUser(): ?\Caroler\Objects\User
    {
        return $this->user;
    }

    /**
     * @return bool|null
     */
    public function getRequireColons(): ?bool
    {
        return $this->requireColons;
    }

    /**
     * @return bool|null
     */
    public function getManaged(): ?bool
    {
        return $this->managed;
    }

    /**
     * @return bool|null
     */
    public function getAnimated(): ?bool
    {
        return $this->animated;
    }

    /**
     * @return bool|null
     */
    public function getAvailable(): ?bool
    {
        return $this->available;
    }
}
