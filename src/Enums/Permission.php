<?php

declare(strict_types=1);

namespace Caroler\Enums;

use MyCLabs\Enum\Enum;

/**
 * Permission enumerator
 *
 * @package Caroler\Traits
 * @see https://discord.com/developers/docs/topics/permissions
 * @method static Permission CREATE_INSTANT_INVITE()
 * @method static Permission KICK_MEMBERS()
 * @method static Permission BAN_MEMBERS()
 * @method static Permission ADMINISTRATOR()
 * @method static Permission MANAGE_CHANNELS()
 * @method static Permission MANAGE_GUILD()
 * @method static Permission ADD_REACTIONS()
 * @method static Permission VIEW_AUDIT_LOG()
 * @method static Permission PRIORITY_SPEAKER()
 * @method static Permission STREAM()
 * @method static Permission VIEW_CHANNEL()
 * @method static Permission SEND_MESSAGES()
 * @method static Permission SEND_TTS_MESSAGES()
 * @method static Permission MANAGE_MESSAGES()
 * @method static Permission EMBED_LINKS()
 * @method static Permission ATTACH_FILES()
 * @method static Permission READ_MESSAGE_HISTORY()
 * @method static Permission MENTION_EVERYONE()
 * @method static Permission USE_EXTERNAL_EMOJIS()
 * @method static Permission VIEW_GUILD_INSIGHTS()
 * @method static Permission CONNECT()
 * @method static Permission SPEAK()
 * @method static Permission MUTE_MEMBERS()
 * @method static Permission DEAFEN_MEMBERS()
 * @method static Permission MOVE_MEMBERS()
 * @method static Permission USE_VAD()
 * @method static Permission CHANGE_NICKNAME()
 * @method static Permission MANAGE_NICKNAMES()
 * @method static Permission MANAGE_ROLES()
 * @method static Permission MANAGE_WEBHOOKS()
 * @method static Permission MANAGE_EMOJIS()
 */
class Permission extends Enum
{
    private const CREATE_INSTANT_INVITE = 0x00000001;
    private const KICK_MEMBERS = 0x00000002;
    private const BAN_MEMBERS = 0x00000004;
    private const ADMINISTRATOR = 0x00000008;
    private const MANAGE_CHANNELS = 0x00000010;
    private const MANAGE_GUILD = 0x00000020;
    private const ADD_REACTIONS = 0x00000040;
    private const VIEW_AUDIT_LOG = 0x00000080;
    private const PRIORITY_SPEAKER = 0x00000100;
    private const STREAM = 0x00000200;
    private const VIEW_CHANNEL = 0x00000400;
    private const SEND_MESSAGES = 0x00000800;
    private const SEND_TTS_MESSAGES = 0x00001000;
    private const MANAGE_MESSAGES = 0x00002000;
    private const EMBED_LINKS = 0x00004000;
    private const ATTACH_FILES = 0x00008000;
    private const READ_MESSAGE_HISTORY = 0x00010000;
    private const MENTION_EVERYONE = 0x00020000;
    private const USE_EXTERNAL_EMOJIS = 0x00040000;
    private const VIEW_GUILD_INSIGHTS = 0x00080000;
    private const CONNECT = 0x00100000;
    private const SPEAK = 0x00200000;
    private const MUTE_MEMBERS = 0x00400000;
    private const DEAFEN_MEMBERS = 0x00800000;
    private const MOVE_MEMBERS = 0x01000000;
    private const USE_VAD = 0x02000000;
    private const CHANGE_NICKNAME = 0x04000000;
    private const MANAGE_NICKNAMES = 0x08000000;
    private const MANAGE_ROLES = 0x10000000;
    private const MANAGE_WEBHOOKS = 0x20000000;
    private const MANAGE_EMOJIS = 0x40000000;
}
