<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Channel Object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#message-object
 */
class Channel extends AbstractObject implements ObjectInterface
{
    /**
     * @var string Channel id
     */
    protected $id;

    /**
     * @var int Channel type
     */
    protected $type;

    /**
     * @var string Guild id
     */
    protected $guildId;

    /**
     * @var int|null Channel sorting position
     */
    protected $position;

    /**
     * @var \Caroler\Objects\Overwrite[] Explicit member and role permission overwrites
     */
    protected $permissionOverwrites;

    /**
     * @var string|null Channel name
     */
    protected $name;

    /**
     * @var string|null Channel topic
     */
    protected $topic;

    /**
     * @var bool Whether channel is nsfw
     */
    protected $nsfw;

    /**
     * @var string|null Id of last message sent in channel
     */
    protected $lastMessageId;

    /**
     * @var int Voice channel bitrate (in bits)
     */
    protected $bitrate;

    /**
     * @var int Voice channel user limit
     */
    protected $userLimit;

    /**
     * @var int Message wait interval for users (in seconds)
     */
    protected $rateLimitPerUser;

    /**
     * @var \Caroler\Objects\User[] DM recipients
     */
    protected $recipients;

    /**
     * @var string|null Icon hash
     */
    protected $icon;

    /**
     * @var string DM creator id
     */
    protected $ownerId;

    /**
     * @var string DM creator application id
     */
    protected $applicationId;

    /**
     * @var string|null Channel parent category id
     */
    protected $parentId;

    /**
     * @var string Last pinned message timestamp
     */
    protected $lastPinTimestamp;
}
