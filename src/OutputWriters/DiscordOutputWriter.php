<?php

declare(strict_types=1);

namespace Caroler\OutputWriters;

use Caroler\Caroler;
use Caroler\Resources\Channel;

/**
 * Discord Output Writer class
 *
 * @package Caroler\OutputWriters
 */
class DiscordOutputWriter extends AbstractOutputWriter implements OutputWriterInterface
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
     * @throws \Caroler\Exceptions\InvalidArgumentException
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function write($messages, string $type = null): OutputWriterInterface
    {
        $messages = $this->process($messages, $type);

        $channel = (new Channel())->prepare($this->channelId, $this->caroler);

        foreach ($messages as $message) {
            $channel->createMessage($message);
        }

        return $this;
    }
}
