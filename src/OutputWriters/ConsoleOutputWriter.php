<?php

declare(strict_types=1);

namespace Caroler\OutputWriters;

/**
 * ConsoleOutputWriter class
 *
 * @package Caroler\OutputWriters
 */
class ConsoleOutputWriter extends AbstractOutputWriter implements OutputWriterInterface
{
    /**
     * @inheritDoc
     */
    public function write($messages, string $type = null): OutputWriterInterface
    {
        $messages = $this->process($messages, $type);

        foreach ($messages as $message) {
            echo $this->prefixTimestamp("$message\n");
        }

        return $this;
    }
}
