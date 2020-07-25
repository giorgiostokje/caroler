<?php

declare(strict_types=1);

namespace Caroler\Objects;

use stdClass;

/**
 * User Object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/user#user-object
 */
class User extends AbstractObject implements ObjectInterface
{
    protected $id;
    protected $username;
    protected $discriminator;
    protected $avatar;
    protected $bot;
    protected $system;
    protected $mfaEnabled;
    protected $locale;
    protected $verified;
    protected $email;
    protected $flags;
    protected $premiumType;
    protected $publicFlags;

    /**
     * @inheritDoc
     */
    public function prepare(stdClass $data): ObjectInterface
    {
        return parent::prepare($data);
    }
}
