<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Message object class
 *
 * @package Caroler\Objects
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
     * @var string|null Message guild id
     */
    protected $guildId;

    /**
     * @var \Caroler\Objects\User Message author
     */
    protected $author;

    /**
     * @var \Caroler\Objects\GuildMember|null Message author member properties
     */
    protected $member;

    /**
     * @var string Message contents
     */
    protected $content;

    /**
     * @var string Message sent ISO8601 timestamp
     */
    protected $timestamp;

    /**
     * @var string|null Message edited ISO8601 timestamp
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
     * @var \Caroler\Objects\User[] Message user mentions
     */
    protected $mentions;

    /**
     * @var \Caroler\Objects\Role[] Message role mentions
     */
    protected $mentionRoles;

    /**
     * @var \Caroler\Objects\ChannelMention[]|null Message channel mentions
     */
    protected $mentionChannels;

    /**
     * @var \Caroler\Objects\Attachment[] Message attachments
     */
    protected $attachments;

    /**
     * @var \Caroler\Objects\Embed[] Message embedded content
     */
    protected $embeds;

    /**
     * @var \Caroler\Objects\Reaction[]|null Message reactions
     */
    protected $reactions;

    /**
     * @var int|string|null Used for validating if a message was sent
     */
    protected $nonce;

    /**
     * @var bool Whether the message is pinned
     */
    protected $pinned;

    /**
     * @var string|null Message webhook id
     */
    protected $webhookId;

    /**
     * @var int Message type
     */
    protected $type;

    /**
     * @var array|null Sent with Rich Presence-related chat embeds
     */
    protected $activity;

    /**
     * @var array|null Sent with Rich Presence-related chat embeds
     */
    protected $application;

    /**
     * @var array|null Crossposted message reference data
     */
    protected $messageReference;

    /**
     * @var int|null Message flags
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
    public function getAuthor(): \Caroler\Objects\User
    {
        return $this->author;
    }

    /**
     * @return \Caroler\Objects\GuildMember|null
     */
    public function getMember(): ?\Caroler\Objects\GuildMember
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
     * @return array|null
     */
    public function getActivity(): ?array
    {
        return $this->activity;
    }

    /**
     * @return array|null
     */
    public function getApplication(): ?array
    {
        return $this->application;
    }

    /**
     * @return array|null
     */
    public function getMessageReference(): ?array
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
