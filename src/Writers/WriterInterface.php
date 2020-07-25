<?php

declare(strict_types=1);

namespace Caroler\Writers;

/**
 * Common Writer interface
 *
 * All Writer classes must implement this interface!
 *
 * @package Caroler\Writers
 */
interface WriterInterface
{
    /**
     * Writes one or more messages to the output writer.
     *
     * @param string|string[] $messages
     * @param string $type info|comment|question|error
     *
     * @return \Caroler\Writers\WriterInterface
     */
    public function write($messages, string $type = null): WriterInterface;
}
