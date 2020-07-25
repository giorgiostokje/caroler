<?php

declare(strict_types=1);

namespace Caroler\OutputWriters;

/**
 * Common OutputWriter functionality
 *
 * @package Caroler\OutputWriters
 */
abstract class AbstractOutputWriter implements OutputWriterInterface
{
    /**
     * Validates that messages are strings.
     *
     * @param string|string[] $messages
     *
     * @return \Caroler\OutputWriters\OutputWriterInterface
     */
    protected function validateMessages($messages): OutputWriterInterface
    {
        if (!is_string($messages) && !is_array($messages)) {
            throw new \InvalidArgumentException("Message must be a string or an array!");
        }

        if (is_array($messages)) {
            foreach ($messages as $message) {
                if (!is_string($message)) {
                    throw new \InvalidArgumentException("Array of messages must contain only strings!");
                }
            }
        }

        return $this;
    }

    /**
     * Validates that the message type is valid.
     *
     * @param string|null $type
     *
     * @return \Caroler\OutputWriters\OutputWriterInterface
     */
    protected function validateMessageType(?string $type): OutputWriterInterface
    {
        if (!is_null($type) && !in_array($type, ['info', 'comment', 'question', 'error'])) {
            throw new \InvalidArgumentException("Invalid message type provided!");
        }

        return $this;
    }

    /**
     * Converts a single message to an array, for processing purposes.
     *
     * @param string|string[] $messages
     *
     * @return array
     */
    protected function convertToArray($messages): array
    {
        if (is_array($messages)) {
            return $messages;
        } else {
            return [$messages];
        }
    }

    /**
     * Executes the complete message processing routine.
     *
     * @param string|string[] $messages
     * @param string|null $type
     *
     * @return array
     */
    protected function process($messages, ?string $type): array
    {
        return $this->validateMessages($messages)->validateMessageType($type)->convertToArray($messages);
    }

    /**
     * Prefixes the current date and time to the message.
     *
     * @param $message
     *
     * @return string
     */
    protected function prefixTimestamp($message): string
    {
        return "[" . date("Y/m/d H:i:s") . "] $message";
    }
}
