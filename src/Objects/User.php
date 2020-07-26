<?php

declare(strict_types=1);

namespace Caroler\Objects;

use stdClass;

/**
 * User object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/user#user-object
 */
class User extends AbstractObject implements ObjectInterface
{
    public $id;
    public $username;
    public $discriminator;
    public $avatar;
    public $bot;
    public $system;
    public $mfaEnabled;
    public $locale;
    public $verified;
    public $email;
    public $flags;
    public $premiumType;
    public $publicFlags;

    /**
     * @inheritDoc
     */
    public function prepare(stdClass $data): ObjectInterface
    {
        return parent::prepare($data);
    }
}
