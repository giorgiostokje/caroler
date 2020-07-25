<?php

declare(strict_types=1);

namespace Caroler\Writers;

/**
 * ConsoleWriter class
 *
 * @package Caroler\Writers
 */
class ConsoleWriter extends AbstractWriter implements WriterInterface
{
    /**
     * @inheritDoc
     */
    public function write($messages, string $type = null): WriterInterface
    {
        $messages = $this->process($messages, $type);

        foreach ($messages as $message) {
            echo $this->prefixTimestamp("$message\n");
        }

        return $this;
    }
}
