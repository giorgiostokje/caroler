<?php

declare(strict_types=1);

namespace Caroler\Traits;

use Caroler\Enums\Permission;

/**
 * Permission helper trait
 *
 * @package Caroler\Traits
 * @see https://discord.com/developers/docs/topics/permissions
 */
trait HasPermissions
{
    /**
     * @return bool
     */
    public function canCreateInstantInvite(): bool
    {
        return $this->hasPermission(Permission::CREATE_INSTANT_INVITE());
    }

    /**
     * @return bool
     */
    public function canKickMembers(): bool
    {
        return $this->hasPermission(Permission::KICK_MEMBERS());
    }

    /**
     * @return bool
     */
    public function canBanMembers(): bool
    {
        return $this->hasPermission(Permission::BAN_MEMBERS());
    }

    /**
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->hasPermission(Permission::ADMINISTRATOR());
    }

    /**
     * @return bool
     */
    public function canManageChannels(): bool
    {
        return $this->hasPermission(Permission::MANAGE_CHANNELS());
    }

    /**
     * @return bool
     */
    public function canManageGuild(): bool
    {
        return $this->hasPermission(Permission::MANAGE_GUILD());
    }

    /**
     * @return bool
     */
    public function canAddReactions(): bool
    {
        return $this->hasPermission(Permission::ADD_REACTIONS());
    }

    /**
     * @return bool
     */
    public function canViewAuditLog(): bool
    {
        return $this->hasPermission(Permission::VIEW_AUDIT_LOG());
    }

    /**
     * @return bool
     */
    public function isPrioritySpeaker(): bool
    {
        return $this->hasPermission(Permission::PRIORITY_SPEAKER());
    }

    /**
     * @return bool
     */
    public function canStream(): bool
    {
        return $this->hasPermission(Permission::STREAM());
    }

    /**
     * @return bool
     */
    public function canViewChannel(): bool
    {
        return $this->hasPermission(Permission::VIEW_CHANNEL());
    }

    /**
     * @return bool
     */
    public function canSendMessages(): bool
    {
        return $this->hasPermission(Permission::SEND_MESSAGES());
    }

    /**
     * @return bool
     */
    public function canSendTtsMessages(): bool
    {
        return $this->hasPermission(Permission::SEND_TTS_MESSAGES());
    }

    /**
     * @return bool
     */
    public function canManageMessages(): bool
    {
        return $this->hasPermission(Permission::MANAGE_MESSAGES());
    }

    /**
     * @return bool
     */
    public function canEmbedLinks(): bool
    {
        return $this->hasPermission(Permission::EMBED_LINKS());
    }

    /**
     * @return bool
     */
    public function canAttachFiles(): bool
    {
        return $this->hasPermission(Permission::ATTACH_FILES());
    }

    /**
     * @return bool
     */
    public function canReadMessageHistory(): bool
    {
        return $this->hasPermission(Permission::READ_MESSAGE_HISTORY());
    }

    /**
     * @return bool
     */
    public function canMentionEveryone(): bool
    {
        return $this->hasPermission(Permission::MENTION_EVERYONE());
    }

    /**
     * @return bool
     */
    public function canUseExternalEmojis(): bool
    {
        return $this->hasPermission(Permission::USE_EXTERNAL_EMOJIS());
    }

    /**
     * @return bool
     */
    public function canViewGuildInsights(): bool
    {
        return $this->hasPermission(Permission::VIEW_GUILD_INSIGHTS());
    }

    /**
     * @return bool
     */
    public function canConnect(): bool
    {
        return $this->hasPermission(Permission::CONNECT());
    }

    /**
     * @return bool
     */
    public function canSpeak(): bool
    {
        return $this->hasPermission(Permission::SPEAK());
    }

    /**
     * @return bool
     */
    public function canMuteMembers(): bool
    {
        return $this->hasPermission(Permission::MUTE_MEMBERS());
    }

    /**
     * @return bool
     */
    public function canDeafenMembers(): bool
    {
        return $this->hasPermission(Permission::DEAFEN_MEMBERS());
    }

    /**
     * @return bool
     */
    public function canMoveMembers(): bool
    {
        return $this->hasPermission(Permission::MOVE_MEMBERS());
    }

    /**
     * @return bool
     */
    public function canUseVad(): bool
    {
        return $this->hasPermission(Permission::USE_VAD());
    }

    /**
     * @return bool
     */
    public function canChangeNickname(): bool
    {
        return $this->hasPermission(Permission::CHANGE_NICKNAME());
    }

    /**
     * @return bool
     */
    public function canManageNicknames(): bool
    {
        return $this->hasPermission(Permission::MANAGE_NICKNAMES());
    }

    /**
     * @return bool
     */
    public function canManageRoles(): bool
    {
        return $this->hasPermission(Permission::MANAGE_ROLES());
    }

    /**
     * @return bool
     */
    public function canManageWebhooks(): bool
    {
        return $this->hasPermission(Permission::MANAGE_WEBHOOKS());
    }

    /**
     * @return bool
     */
    public function canManageEmojis(): bool
    {
        return $this->hasPermission(Permission::MANAGE_EMOJIS());
    }

    /**
     * @param \Caroler\Enums\Permission $permission
     *
     * @return bool
     */
    private function hasPermission(Permission $permission): bool
    {
        return !is_null($this->getPermissionsNew())
            ? (
                ($this->getPermissionsNew() & hexdec(Permission::ADMINISTRATOR()))
                    === hexdec(Permission::ADMINISTRATOR())
                || ($this->getPermissionsNew() & hexdec($permission))
                    === hexdec($permission)
            )
            : false;
    }
}
