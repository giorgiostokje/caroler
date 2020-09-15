<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Channel object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#channel-object
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
     * @var string|null Channel guild id
     */
    protected $guildId;

    /**
     * @var int|null Channel sorting position
     */
    protected $position;

    /**
     * @var \Caroler\Objects\Overwrite[]|null Explicit channel member and role permission overwrites
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
     * @var bool|null Whether the channel is nsfw
     */
    protected $nsfw;

    /**
     * @var string|null Last channel message id
     */
    protected $lastMessageId;

    /**
     * @var int|null Voice channel bitrate (in bits)
     */
    protected $bitrate;

    /**
     * @var int|null Voice channel user limit
     */
    protected $userLimit;

    /**
     * @var int|null Channel message wait interval for users (in seconds)
     */
    protected $rateLimitPerUser;

    /**
     * @var \Caroler\Objects\User[]|null DM recipients
     */
    protected $recipients;

    /**
     * @var string|null Channel icon hash
     */
    protected $icon;

    /**
     * @var string|null DM creator id
     */
    protected $ownerId;

    /**
     * @var string|null Bot-created DM creator application id
     */
    protected $applicationId;

    /**
     * @var string|null Channel parent category id
     */
    protected $parentId;

    /**
     * @var string|null Last pinned channel message ISO8601 timestamp
     */
    protected $lastPinTimestamp;

    /**
     * @inheritDoc
     */
    public function prepare(array $data): ObjectInterface
    {
        if (isset($data['permission_overwrites'])) {
            $this->permissionOverwrites = $this->transform($data['permission_overwrites'], Overwrite::class);
            unset($data['permission_overwrites']);
        }

        if (isset($data['recipients'])) {
            $this->recipients = $this->transform($data['recipients'], User::class);
            unset($data['recipients']);
        }

        parent::prepare($data);

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getGuildId(): ?string
    {
        return $this->guildId;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @return \Caroler\Objects\Overwrite[]|null
     */
    public function getPermissionOverwrites(): ?array
    {
        return $this->permissionOverwrites;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getTopic(): ?string
    {
        return $this->topic;
    }

    /**
     * @return bool|null
     */
    public function getNsfw(): ?bool
    {
        return $this->nsfw;
    }

    /**
     * @return string|null
     */
    public function getLastMessageId(): ?string
    {
        return $this->lastMessageId;
    }

    /**
     * @return int|null
     */
    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return int|null
     */
    public function getUserLimit(): ?int
    {
        return $this->userLimit;
    }

    /**
     * @return int|null
     */
    public function getRateLimitPerUser(): ?int
    {
        return $this->rateLimitPerUser;
    }

    /**
     * @return \Caroler\Objects\User[]|null
     */
    public function getRecipients(): ?array
    {
        return $this->recipients;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @return string|null
     */
    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    /**
     * @return string|null
     */
    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @return string|null
     */
    public function getLastPinTimestamp(): ?string
    {
        return $this->lastPinTimestamp;
    }
}
