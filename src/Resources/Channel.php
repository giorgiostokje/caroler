<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Caroler;
use Caroler\Objects\Embed;
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

    public function prepare($context, Caroler $caroler): ResourceInterface
    {
        return parent::prepare(
            $context instanceof Message
                ? $context->getChannelId() : $context,
            $caroler
        );
    }

    /**
     * Creates and sends a message.
     *
     * @param string|null $message
     *
     * @param \Caroler\Objects\Embed|null $embed
     *
     * @return \Caroler\Objects\Message|null
     */
    public function createMessage(?string $message, Embed $embed = null): ?Message
    {
        if (!isset($message) && !isset($embed)) {
            return null;
        }

        return !($response = $this->post("$this->context/messages", [
            'content' => $message,
            'embed' => isset($embed) ? $embed->toArray() : null,
        ])) ? null
            : (new Message())->prepare($response);
    }
}
