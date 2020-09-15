<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * User object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/user#user-object
 */
class User extends AbstractObject implements ObjectInterface
{
    /**
     * @var string User id
     */
    protected $id;

    /**
     * @var string User username (not unique)
     */
    protected $username;

    /**
     * @var string User 4-digit Discord tag
     */
    protected $discriminator;

    /**
     * @var string|null User avatar hash
     */
    protected $avatar;

    /**
     * @var bool|null Whether the user belongs to an OAuth2 app
     */
    protected $bot;

    /**
     * @var bool|null Whether the user is an Official Discord System user
     */
    protected $system;

    /**
     * @var bool|null Whether the user account has 2FA enabled
     */
    protected $mfaEnabled;

    /**
     * @var string|null User language option of choice
     */
    protected $locale;

    /**
     * @var bool|null Whether the user account's email is verified
     */
    protected $verified;

    /**
     * @var string|null User email
     */
    protected $email;

    /**
     * @var int|null User account flags
     */
    protected $flags;

    /**
     * @var int|null User account Nitro subscription type
     */
    protected $premiumType;

    /**
     * @var int|null User account public flags
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
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return bool|null
     */
    public function getBot(): ?bool
    {
        return $this->bot;
    }

    /**
     * @return bool|null
     */
    public function getSystem(): ?bool
    {
        return $this->system;
    }

    /**
     * @return bool|null
     */
    public function getMfaEnabled(): ?bool
    {
        return $this->mfaEnabled;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return bool|null
     */
    public function getVerified(): ?bool
    {
        return $this->verified;
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
