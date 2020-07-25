<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Channel Object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#message-object
 */
class Message extends AbstractObject implements ObjectInterface
{
    /**
     * @var string Message id
     */
    public $id;

    /**
     * @var string Message channel id
     */
    public $channelId;

    /**
     * @var string Message guild id
     */
    public $guildId;

    /**
     * @var \Caroler\Objects\User Message author
     */
    public $author;

    /**
     * @var \Caroler\Objects\GuildMember Message author's member properties
     */
    public $member;

    /**
     * @var string Message contents
     */
    public $content;

    /**
     * @var string Message sent timestamp
     */
    public $timestamp;

    /**
     * @var string|null Message edited timestamp
     */
    public $editedTimestamp;

    /**
     * @var bool Whether message was TTS
     */
    public $tts;

    /**
     * @var bool Whether message mentions everyone
     */
    public $mentionEveryone;

    /**
     * @var \Caroler\Objects\User[] Message user mentions
     */
    public $mentions;

    /**
     * @var \Caroler\Objects\Role[] Message role mentions
     */
    public $mentionRoles;

    /**
     * @var \Caroler\Objects\ChannelMention[] Message role mentions
     */
    public $mentionChannels;

    /**
     * @var \Caroler\Objects\Attachment[] Message file attachments
     */
    public $attachments;

    /**
     * @var \Caroler\Objects\Embed[] Message embedded content
     */
    public $embeds;

    /**
     * @var \Caroler\Objects\Reaction[] Message reactions
     */
    public $reactions;

    /**
     * @var int|string Used for validating a message was sent.
     */
    public $nonce;

    /**
     * @var bool Whether message is pinned
     */
    public $pinned;

    /**
     * @var string Message webhook id
     */
    public $webhookId;

    /**
     * @var int Message type
     */
    public $type;

    /**
     * @var \Caroler\Objects\MessageActivity
     */
    public $activity;

    /**
     * @var \Caroler\Objects\MessageApplication
     */
    public $application;

    /**
     * @var \Caroler\Objects\MessageReference
     */
    public $messageReference;

    /**
     * @var int Message flags
     */
    public $flags;
}
