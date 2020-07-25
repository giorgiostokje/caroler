<?php

declare(strict_types=1);

namespace Caroler\Writers;

use Caroler\Caroler;

/**
 * DiscordWriter class
 *
 * @package Caroler\Writers
 */
class DiscordWriter extends AbstractWriter implements WriterInterface
{
    /**
     * @var string Id of the channel to write to
     */
    private $channelId;

    /**
     * @var \Caroler\Caroler Application instance
     */
    private $caroler;

    /**
     * @param string $channelId
     * @param \Caroler\Caroler $caroler
     */
    public function __construct(string $channelId, Caroler $caroler)
    {
        $this->channelId = $channelId;
        $this->caroler = $caroler;
    }

    /**
     * @inheritDoc
     */
    public function write($messages, string $type = null): WriterInterface
    {
        $messages = $this->process($messages, $type);

        foreach ($messages as $message) {
            $this->caroler->send($message, $this->channelId);
        }

        return $this;
    }
}
