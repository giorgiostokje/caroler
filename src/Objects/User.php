<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * User Object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/user#user-object
 */
class User extends AbstractObject implements ObjectInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $discriminator;

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var bool
     */
    protected $bot;

    /**
     * @var bool
     */
    protected $system;

    /**
     * @var bool
     */
    protected $mfaEnabled;

    /**
     * @var string|null
     */
    protected $locale;

    /**
     * @var bool
     */
    protected $verified;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var int|null
     */
    protected $flags;

    /**
     * @var int|null
     */
    protected $premiumType;

    /**
     * @var int|null
     */
    protected $publicFlags;

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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getDiscriminator(): string
    {
        return $this->discriminator;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->bot ?? false;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->system ?? false;
    }

    /**
     * @return bool
     */
    public function isMfaEnabled(): bool
    {
        return $this->mfaEnabled ?? false;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified ?? false;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return int|null
     */
    public function getFlags(): ?int
    {
        return $this->flags;
    }

    /**
     * @return int|null
     */
    public function getPremiumType(): ?int
    {
        return $this->premiumType;
    }

    /**
     * @return int|null
     */
    public function getPublicFlags(): ?int
    {
        return $this->publicFlags;
    }
}
