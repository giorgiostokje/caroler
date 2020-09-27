<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Ban object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/guild#ban-object
 */
class Ban extends AbstractObject implements ObjectInterface
{
    /**
     * @var string|null Ban reason
     */
    protected $reason;

    /**
     * @var \Caroler\Objects\User Banned User
     */
    protected $user;

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
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @return \Caroler\Objects\User
     */
    public function getUser(): \Caroler\Objects\User
    {
        return $this->user;
    }
}
