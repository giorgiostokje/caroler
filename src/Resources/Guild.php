<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Objects\Ban;
use Caroler\Objects\Guild as GuildObject;
use Caroler\Objects\GuildMember;
use Caroler\Objects\Role;
use Caroler\Objects\User;

/**
 * Resource class facilitating communications with the various Discord REST API endpoints in the Guild resource.
 *
 * @package Caroler\Resources
 * @see https://discord.com/developers/docs/resources/guild
 */
class Guild extends AbstractResource implements ResourceInterface
{
    /**
     * @var string REST API resource
     */
    public const API_RESOURCE = 'guilds/';

    /**
     * @param bool $withCounts
     *
     * @return \Caroler\Objects\Guild|null
     */
    public function getGuild(bool $withCounts = false): ?GuildObject
    {
        return !($response = $this->get($this->context, ['with_counts' => $withCounts]))
            ? null
            : (new GuildObject())->prepare($response);
    }

    /**
     * @param string $userId
     *
     * @return \Caroler\Objects\GuildMember|null
     */
    public function getGuildMember(string $userId): ?GuildMember
    {
        return !($response = $this->get("$this->context/members/$userId"))
            ? null
            : (new GuildMember())->prepare($response);
    }

    /**
     * @return GuildMember[]|null
     */
    public function listGuildMembers(): ?array
    {
        $members = [];
        $after = null;

        do {
            $currentIteration = $this->get("$this->context/members", [
                'limit' => 1000,
                'after' => $after,
            ]);

            if (count($members) === 0 && !$currentIteration) {
                return null;
            }

            foreach ($currentIteration as $key => $member) {
                $currentIteration[$key] = (new GuildMember())->prepare($member);
            }

            if (!empty($currentIteration)) {
                $members = array_merge($members, $currentIteration);
                $after = $currentIteration[count($currentIteration) - 1]->getUser()->getId();
            }
        } while (count($currentIteration) === 1000);

        return $members;
    }

    /**
     * @return array|null
     */
    public function getGuildBans(): ?array
    {
        if (!($bans = $this->get("$this->context/bans"))) {
            return null;
        }

        foreach ($bans as $key => $ban) {
            $bans[$key] = (new Ban())->prepare($ban);
        }

        return $bans;
    }

    /**
     * @param  string  $userId
     *
     * @return bool
     */
    public function removeGuildBan(string $userId): bool
    {
        return $this->delete("$this->context/bans/$userId");
    }

    /**
     * @return \Caroler\Objects\Role[]|null
     */
    public function getGuildRoles(): ?array
    {
        if (!($roles = $this->get("$this->context/roles"))) {
            return null;
        }

        foreach ($roles as $key => $role) {
            $roles[$key] = (new Role())->prepare($role);
        }

        return $roles;
    }
}
