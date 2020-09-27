<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Objects\Guild;
use Caroler\Objects\User as UserObject;

/**
 * Resource class facilitating communications with the various Discord REST API endpoints in the User resource.
 *
 * @package Caroler\Resources
 * @see https://discord.com/developers/docs/resources/guild
 */
class User extends AbstractResource implements ResourceInterface
{
    /**
     * @var string REST API resource
     */
    public const API_RESOURCE = 'users/';

    public function getCurrentUser(): UserObject
    {
        return $this->getUser();
    }

    /**
     * @return \Caroler\Objects\User
     */
    public function getUser(): UserObject
    {
        return (new UserObject())->prepare($this->get($this->context));
    }

    /**
     * @return \Caroler\Objects\Guild[]
     */
    public function getCurrentUserGuilds(): array
    {
        foreach ($guilds = $this->get("$this->context/guilds") as $key => $guild) {
            $guilds[$key] = (new Guild())->prepare($guild);
        }

        return $guilds;
    }
}
