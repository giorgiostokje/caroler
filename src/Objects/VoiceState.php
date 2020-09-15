<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Voice State object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/voice#voice-state-object
 */
class VoiceState extends AbstractObject implements ObjectInterface
{
    /**
     * @var string|null Voice state guild id
     */
    protected $guildId;

    /**
     * @var string|null Channel id the user is connected to
     */
    protected $channelId;

    /**
     * @var string Voice state user id
     */
    protected $userId;

    /**
     * @var \Caroler\Objects\GuildMember|null Voice state guild member
     */
    protected $member;

    /**
     * @var string Voice state session id
     */
    protected $sessionId;

    /**
     * @var bool Whether the user is deafened by the server
     */
    protected $deaf;

    /**
     * @var bool Whether the user is muted by the server
     */
    protected $mute;

    /**
     * @var bool Whether the user is locally deafened
     */
    protected $selfDeaf;

    /**
     * @var bool Whether the user is locally muted
     */
    protected $selfMute;

    /**
     * @var bool|null Whether the user is streaming using "Go Live"
     */
    protected $selfStream;

    /**
     * @var bool Whether the user's camera is enabled
     */
    protected $selfVideo;

    /**
     * @var bool Whether the user is muted by the current user
     */
    protected $suppress;

    /**
     * @inheritDoc
     */
    public function prepare(array $data): ObjectInterface
    {
        if (isset($data['member'])) {
            $this->member = (new GuildMember())->prepare($data['member']);
            unset($data['member']);
        }

        parent::prepare($data);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGuildId(): ?string
    {
        return $this->guildId;
    }

    /**
     * @return string|null
     */
    public function getChannelId(): ?string
    {
        return $this->channelId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
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
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @return bool
     */
    public function isDeaf(): bool
    {
        return $this->deaf;
    }

    /**
     * @return bool
     */
    public function isMute(): bool
    {
        return $this->mute;
    }

    /**
     * @return bool
     */
    public function isSelfDeaf(): bool
    {
        return $this->selfDeaf;
    }

    /**
     * @return bool
     */
    public function isSelfMute(): bool
    {
        return $this->selfMute;
    }

    /**
     * @return bool|null
     */
    public function getSelfStream(): ?bool
    {
        return $this->selfStream;
    }

    /**
     * @return bool
     */
    public function isSelfVideo(): bool
    {
        return $this->selfVideo;
    }

    /**
     * @return bool
     */
    public function isSuppress(): bool
    {
        return $this->suppress;
    }
}
