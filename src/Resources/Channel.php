<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Objects\Channel as ChannelObject;
use Caroler\Objects\Message;

/**
 * Channel resource class
 *
 * @package Caroler\Resources
 * @see https://discord.com/developers/docs/resources/channel
 */
class Channel extends AbstractResource implements ResourceInterface
{
    /**
     * @var string REST API resource
     */
    public const API_RESOURCE = 'channels/';

    /**
     * @return \Caroler\Objects\Channel
     */
    public function getChannel(): ChannelObject
    {
        // TODO: implement method
    }

    /**
     * @return \Caroler\Objects\Channel
     */
    public function modifyChannel(): ChannelObject
    {
        // TODO: implement method
    }

    /**
     * @return \Caroler\Objects\Channel
     */
    public function deleteChannel(): ChannelObject
    {
        // TODO: implement method
    }

    /**
     * @return \Caroler\Objects\Message[]
     */
    public function getChannelMessages(): array
    {
        // TODO: implement method
    }

    /**
     * @return \Caroler\Objects\Message
     */
    public function getChannelMessage(): Message
    {
        // TODO: implement method
    }

    /**
     * @param string|array $message
     *
     * @return \Caroler\Objects\Message
     */
    public function createMessage($message): Message
    {
        if (!$this->context instanceof Message && !is_string($this->context)) {
            throw new \InvalidArgumentException("Context must be either a Message object or a string!");
        }

        $channelId = $this->context instanceof Message ? $this->context->getChannelId() : $this->context;
        $data = ['content' => $message['content'] ?? $message];
        !isset($message['embed']) ?: $data['embed'] = $message['embed']->toArray();

        return (new Message())->prepare($this->post("$channelId/messages", $data));
    }

    /**
     * @return bool
     */
    public function createReaction(): bool
    {
        // Todo: implement method
    }

    /**
     * @return bool
     */
    public function deleteOwnReaction(): bool
    {
        // Todo: implement method
    }

    /**
     * @return \Caroler\Objects\User[]
     */
    public function getReactions(): array
    {
        // Todo: implement method
    }
}
