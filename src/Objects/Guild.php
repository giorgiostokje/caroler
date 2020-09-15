<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Guild Object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/guild#guild-object
 */
class Guild extends AbstractObject implements ObjectInterface
{
    /**
     * @var string Guild id
     */
    protected $id;

    /**
     * @var string Guild name
     */
    protected $name;

    /**
     * @var string|null Guild icon hash
     */
    protected $icon;

    /**
     * @var string|null Guild splash hash
     */
    protected $splash;

    /**
     * @var string|null Guild discovery splash hash
     */
    protected $discoverySplash;

    /**
     * @var bool|null Whether the user is guild owner
     */
    protected $owner;

    /**
     * @var string Guild owner id
     */
    protected $ownerId;

    /**
     * @var int|null Legacy user total guild permissions
     */
    protected $permissions;

    /**
     * @var string|null User total guild permissions
     */
    protected $permissionsNew;

    /**
     * @var string Guild voice region
     */
    protected $region;

    /**
     * @var string|null  Guild AFK channel id
     */
    protected $afkChannelId;

    /**
     * @var int Guild AFK timeout in seconds
     */
    protected $afkTimeout;

    /**
     * @var int Guild verification level requirement
     */
    protected $verificationLevel;

    /**
     * @var int Default guild message notifications level
     */
    protected $defaultMessageNotifications;

    /**
     * @var int Guild explicit content filter level
     */
    protected $explicitContentFilter;

    /**
     * @var \Caroler\Objects\Role[] Guild roles
     */
    protected $roles;

    /**
     * @var \Caroler\Objects\Emoji[] Custom guild emojis
     */
    protected $emojis;

    /**
     * @var string[] Enabled guild features
     */
    protected $features;

    /**
     * @var int Required guild MFA level
     */
    protected $mfaLevel;

    /**
     * @var string|null Bot-created guild guild creator application id
     */
    protected $applicationId;

    /**
     * @var bool|null Whether the guild widget is enabled
     */
    protected $widgetEnabled;

    /**
     * @var string|null Channel id for widget guild invites
     */
    protected $widgetChannelId;

    /**
     * @var string|null Guild notices channel id
     */
    protected $systemChannelId;

    /**
     * @var int Guild system channel flags
     */
    protected $systemChannelFlags;

    /**
     * @var string|null "PUBLIC" feature guild rules channel id
     */
    protected $rulesChannelId;

    /**
     * @var string|null ISO8601 timestamp format guild join date
     */
    protected $joinedAt;

    /**
     * @var bool|null Whether the guild considered large
     */
    protected $large;

    /**
     * @var bool|null Whether the guild is considered unavailable due to outage
     */
    protected $unavailable;

    /**
     * @var int|null Total guild members
     */
    protected $memberCount;

    /**
     * @var \Caroler\Objects\VoiceState[]|null Member states of members currently in guild voice channels
     */
    protected $voiceStates;

    /**
     * @var \Caroler\Objects\GuildMember[]|null Guild users
     */
    protected $members;

    /**
     * @var \Caroler\Objects\Channel[]|null Guild channels
     */
    protected $channels;

    /**
     * @var \Caroler\Objects\PresenceUpdate[]|null Guild members' presences
     */
    protected $presences;

    /**
     * @var int|null Maximum guild presences
     */
    protected $maxPresences = 25000;

    /**
     * @var int|null Maximum guild members
     */
    protected $maxMembers;

    /**
     * @var string|null Guild vanity URL code
     */
    protected $vanityUrlCode;

    /**
     * @var string|null Discoverable guild description
     */
    protected $description;

    /**
     * @var string|null Guild banner hash
     */
    protected $banner;

    /**
     * @var int Guild premium tier (a.k.a. Server Boost level)
     */
    protected $premiumTier;

    /**
     * @var int|null Guild boosts amount
     */
    protected $premiumSubscriptionCount;

    /**
     * @var string "PUBLIC" feature guild preferred locale
     */
    protected $preferredLocale;

    /**
     * @var string|null "PUBLIC" feature guild Discord notifications channel
     */
    protected $publicUpdatesChannelId;

    /**
     * @var int|null Maximum guild video channel users
     */
    protected $maxVideoChannelUsers;

    /**
     * @var int|null Approximate guild members
     */
    protected $approximateMemberCount;

    /**
     * @var int|null Approximate guild presences
     */
    protected $approximatePresenceCount;

    /**
     * @inheritDoc
     */
    public function prepare(array $data): ObjectInterface
    {
        if (isset($data['roles'])) {
            $this->roles = $this->transform($data['roles'], Role::class);
            unset($data['roles']);
        }

        if (isset($data['emojis'])) {
            $this->emojis = $this->transform($data['emojis'], Emoji::class);
            unset($data['emojis']);
        }

        if (isset($data['voice_states'])) {
            $this->voiceStates = $this->transform($data['voice_states'], VoiceState::class);
            unset($data['voice_states']);
        }

        if (isset($data['members'])) {
            $this->members = $this->transform($data['members'], GuildMember::class);
            unset($data['members']);
        }

        if (isset($data['channels'])) {
            $this->channels = $this->transform($data['channels'], Channel::class);
            unset($data['channels']);
        }

        if (isset($data['presences'])) {
            $this->presences = $this->transform($data['presences'], PresenceUpdate::class);
            unset($data['presences']);
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getSplash(): ?string
    {
        return $this->splash;
    }

    /**
     * @return string|null
     */
    public function getDiscoverySplash(): ?string
    {
        return $this->discoverySplash;
    }

    /**
     * @return bool|null
     */
    public function getOwner(): ?bool
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @return int|null
     */
    public function getPermissions(): ?int
    {
        return $this->permissions;
    }

    /**
     * @return string|null
     */
    public function getPermissionsNew(): ?string
    {
        return $this->permissionsNew;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string|null
     */
    public function getAfkChannelId(): ?string
    {
        return $this->afkChannelId;
    }

    /**
     * @return int
     */
    public function getAfkTimeout(): int
    {
        return $this->afkTimeout;
    }

    /**
     * @return int
     */
    public function getVerificationLevel(): int
    {
        return $this->verificationLevel;
    }

    /**
     * @return int
     */
    public function getDefaultMessageNotifications(): int
    {
        return $this->defaultMessageNotifications;
    }

    /**
     * @return int
     */
    public function getExplicitContentFilter(): int
    {
        return $this->explicitContentFilter;
    }

    /**
     * @return \Caroler\Objects\Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return \Caroler\Objects\Emoji[]
     */
    public function getEmojis(): array
    {
        return $this->emojis;
    }

    /**
     * @return string[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @return int
     */
    public function getMfaLevel(): int
    {
        return $this->mfaLevel;
    }

    /**
     * @return string|null
     */
    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    /**
     * @return bool|null
     */
    public function getWidgetEnabled(): ?bool
    {
        return $this->widgetEnabled;
    }

    /**
     * @return string|null
     */
    public function getWidgetChannelId(): ?string
    {
        return $this->widgetChannelId;
    }

    /**
     * @return string|null
     */
    public function getSystemChannelId(): ?string
    {
        return $this->systemChannelId;
    }

    /**
     * @return int
     */
    public function getSystemChannelFlags(): int
    {
        return $this->systemChannelFlags;
    }

    /**
     * @return string|null
     */
    public function getRulesChannelId(): ?string
    {
        return $this->rulesChannelId;
    }

    /**
     * @return string|null
     */
    public function getJoinedAt(): ?string
    {
        return $this->joinedAt;
    }

    /**
     * @return bool|null
     */
    public function getLarge(): ?bool
    {
        return $this->large;
    }

    /**
     * @return bool|null
     */
    public function getUnavailable(): ?bool
    {
        return $this->unavailable;
    }

    /**
     * @return int|null
     */
    public function getMemberCount(): ?int
    {
        return $this->memberCount;
    }

    /**
     * @return \Caroler\Objects\VoiceState[]|null
     */
    public function getVoiceStates(): ?array
    {
        return $this->voiceStates;
    }

    /**
     * @return \Caroler\Objects\GuildMember[]|null
     */
    public function getMembers(): ?array
    {
        return $this->members;
    }

    /**
     * @return \Caroler\Objects\Channel[]|null
     */
    public function getChannels(): ?array
    {
        return $this->channels;
    }

    /**
     * @return \Caroler\Objects\PresenceUpdate[]|null
     */
    public function getPresences(): ?array
    {
        return $this->presences;
    }

    /**
     * @return int|null
     */
    public function getMaxPresences(): ?int
    {
        return $this->maxPresences;
    }

    /**
     * @return int|null
     */
    public function getMaxMembers(): ?int
    {
        return $this->maxMembers;
    }

    /**
     * @return string|null
     */
    public function getVanityUrlCode(): ?string
    {
        return $this->vanityUrlCode;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getBanner(): ?string
    {
        return $this->banner;
    }

    /**
     * @return int
     */
    public function getPremiumTier(): int
    {
        return $this->premiumTier;
    }

    /**
     * @return int|null
     */
    public function getPremiumSubscriptionCount(): ?int
    {
        return $this->premiumSubscriptionCount;
    }

    /**
     * @return string
     */
    public function getPreferredLocale(): string
    {
        return $this->preferredLocale;
    }

    /**
     * @return string|null
     */
    public function getPublicUpdatesChannelId(): ?string
    {
        return $this->publicUpdatesChannelId;
    }

    /**
     * @return int|null
     */
    public function getMaxVideoChannelUsers(): ?int
    {
        return $this->maxVideoChannelUsers;
    }

    /**
     * @return int|null
     */
    public function getApproximateMemberCount(): ?int
    {
        return $this->approximateMemberCount;
    }

    /**
     * @return int|null
     */
    public function getApproximatePresenceCount(): ?int
    {
        return $this->approximatePresenceCount;
    }
}
