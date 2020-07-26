<?php

declare(strict_types=1);

namespace Caroler\OutputWriters;

/**
 * Common OutputWriter interface
 *
 * All OutputWriter classes must implement this interface!
 *
 * @package Caroler\OutputWriters
 */
interface OutputWriterInterface
{
    /**
     * Writes one or more messages to the output writer.
     *
     * @param string|string[] $messages
     * @param string $type info|comment|question|error
     *
     * @return \Caroler\OutputWriters\OutputWriterInterface
     */
    public function write($messages, string $type = null): OutputWriterInterface;
}
