<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Objects;

/**
 * Channel Object class
 *
 * @package GiorgioStokje\Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#message-object
 */
class Message extends AbstractObject implements ObjectInterface
{
    /**
     * @var string Message id
     */
    protected $id;

    /**
     * @var string Message channel id
     */
    protected $channelId;

    /**
     * @var string Message guild id
     */
    protected $guildId;

    /**
     * @var \GiorgioStokje\Caroler\Objects\User Message author
     */
    protected $author;

    /**
     * @var \GiorgioStokje\Caroler\Objects\GuildMember Message author's member properties
     */
    protected $member;

    /**
     * @var string Message contents
     */
    protected $content;

    /**
     * @var string Message sent timestamp
     */
    protected $timestamp;

    /**
     * @var string|null Message edited timestamp
     */
    protected $editedTimestamp;

    /**
     * @var bool Whether message was TTS
     */
    protected $tts;

    /**
     * @var bool Whether message mentions everyone
     */
    protected $mentionEveryone;

    /**
     * @var \GiorgioStokje\Caroler\Objects\User[] Message user mentions
     */
    protected $mentions;

    /**
     * @var \GiorgioStokje\Caroler\Objects\Role[] Message role mentions
     */
    protected $mentionRoles;

    /**
     * @var \GiorgioStokje\Caroler\Objects\ChannelMention[] Message role mentions
     */
    protected $mentionChannels;

    /**
     * @var \GiorgioStokje\Caroler\Objects\Attachment[] Message file attachments
     */
    protected $attachments;

    /**
     * @var \GiorgioStokje\Caroler\Objects\Embed[] Message embedded content
     */
    protected $embeds;

    /**
     * @var \GiorgioStokje\Caroler\Objects\Reaction[] Message reactions
     */
    protected $reactions;

    /**
     * @var int|string Used for validating a message was sent.
     */
    protected $nonce;

    /**
     * @var bool Whether message is pinned
     */
    protected $pinned;

    /**
     * @var string Message webhook id
     */
    protected $webhookId;

    /**
     * @var int Message type
     */
    protected $type;

    /**
     * @var \GiorgioStokje\Caroler\Objects\MessageActivity
     */
    protected $activity;

    /**
     * @var \GiorgioStokje\Caroler\Objects\MessageApplication
     */
    protected $application;

    /**
     * @var \GiorgioStokje\Caroler\Objects\MessageReference
     */
    protected $messageReference;

    /**
     * @var int Message flags
     */
    protected $flags;
}
