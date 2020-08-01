<?php

declare(strict_types=1);

namespace Caroler\Objects;

use stdClass;

/**
 * Message Object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#message-object
 */
class Message extends AbstractObject implements ObjectInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $channelId;

    /**
     * @var string|null
     */
    protected $guildId;

    /**
     * @var stdClass|\Caroler\Objects\User
     */
    protected $author;

    /**
     * @var \Caroler\Objects\GuildMember|null Message author's member properties
     */
    protected $member;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string ISO8601 timestamp
     */
    protected $timestamp;

    /**
     * @var string|null ISO8601 timestamp
     */
    protected $editedTimestamp;

    /**
     * @var bool
     */
    protected $tts;

    /**
     * @var bool
     */
    protected $mentionEveryone;

    /**
     * @var \Caroler\Objects\User[]
     */
    protected $mentions;

    /**
     * @var \Caroler\Objects\Role[]
     */
    protected $mentionRoles;

    /**
     * @var \Caroler\Objects\ChannelMention[]|null
     */
    protected $mentionChannels;

    /**
     * @var \Caroler\Objects\Attachment[]
     */
    protected $attachments;

    /**
     * @var \Caroler\Objects\Embed[]
     */
    protected $embeds;

    /**
     * @var \Caroler\Objects\Reaction[]|null
     */
    protected $reactions;

    /**
     * @var int|string|null Used for validating if a message was sent.
     */
    protected $nonce;

    /**
     * @var bool
     */
    protected $pinned;

    /**
     * @var string|null
     */
    protected $webhookId;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var \Caroler\Objects\MessageActivity|null
     */
    protected $activity;

    /**
     * @var \Caroler\Objects\MessageApplication|null
     */
    protected $application;

    /**
     * @var \Caroler\Objects\MessageReference|null
     */
    protected $messageReference;

    /**
     * @var int|null
     */
    protected $flags;

    /**
     * @inheritDoc
     */
    public function prepare($data): ObjectInterface
    {
        parent::prepare($data);

        $author = new User();
        $this->author = $author->prepare($this->author);

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
     * @return string
     */
    public function getChannelId(): string
    {
        return $this->channelId;
    }

    /**
     * @return string|null
     */
    public function getGuildId(): ?string
    {
        return $this->guildId;
    }

    /**
     * @return \Caroler\Objects\User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @return \Caroler\Objects\GuildMember|null
     */
    public function getMember(): ?GuildMember
    {
        return $this->member;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string|null
     */
    public function getEditedTimestamp(): ?string
    {
        return $this->editedTimestamp;
    }

    /**
     * @return bool
     */
    public function isTts(): bool
    {
        return $this->tts;
    }

    /**
     * @return bool
     */
    public function isMentionEveryone(): bool
    {
        return $this->mentionEveryone;
    }

    /**
     * @return \Caroler\Objects\User[]
     */
    public function getMentions(): array
    {
        return $this->mentions;
    }

    /**
     * @return \Caroler\Objects\Role[]
     */
    public function getMentionRoles(): array
    {
        return $this->mentionRoles;
    }

    /**
     * @return \Caroler\Objects\ChannelMention[]|null
     */
    public function getMentionChannels(): ?array
    {
        return $this->mentionChannels;
    }

    /**
     * @return \Caroler\Objects\Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return \Caroler\Objects\Embed[]
     */
    public function getEmbeds(): array
    {
        return $this->embeds;
    }

    /**
     * @return \Caroler\Objects\Reaction[]|null
     */
    public function getReactions(): ?array
    {
        return $this->reactions;
    }

    /**
     * @return int|string|null
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @return bool
     */
    public function isPinned(): bool
    {
        return $this->pinned;
    }

    /**
     * @return string|null
     */
    public function getWebhookId(): ?string
    {
        return $this->webhookId;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return \Caroler\Objects\MessageActivity|null
     */
    public function getActivity(): ?MessageActivity
    {
        return $this->activity;
    }

    /**
     * @return \Caroler\Objects\MessageApplication|null
     */
    public function getApplication(): ?MessageApplication
    {
        return $this->application;
    }

    /**
     * @return \Caroler\Objects\MessageReference|null
     */
    public function getMessageReference(): ?MessageReference
    {
        return $this->messageReference;
    }

    /**
     * @return int|null
     */
    public function getFlags(): ?int
    {
        return $this->flags;
    }
}
