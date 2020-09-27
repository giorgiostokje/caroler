<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Exceptions\InvalidArgumentException;
use Caroler\Objects\Message;

/**
 * Resource class facilitating communications with the various Discord REST API endpoints in the Channel resource.
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
     * Creates and sends a message.
     *
     * @param string|array $message
     *
     * @return \Caroler\Objects\Message|null
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function createMessage($message): ?Message
    {
        if (!$this->context instanceof Message && !is_string($this->context)) {
            throw new InvalidArgumentException("Context must be either a Message Object or a string!");
        }

        $channelId = $this->context instanceof Message ? $this->context->getChannelId() : $this->context;
        $data = ['content' => $message['content'] ?? $message];
        !isset($message['embed']) ?: $data['embed'] = $message['embed']->toArray();

        return (new Message())->prepare($this->post("$channelId/messages", $data));
    }
}
