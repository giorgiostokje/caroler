<?php

declare(strict_types=1);

namespace Caroler\OutputWriters;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * SymfonyConsoleOutputWriterAdapter class
 *
 * @package Caroler\OutputWriters
 */
class SymfonyConsoleOutputWriterAdapter extends AbstractOutputWriter implements OutputWriterInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface Symfony Console Output interface
     */
    private $outputWriter;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $outputWriter
     */
    public function __construct(OutputInterface $outputWriter)
    {
        $this->outputWriter = $outputWriter;
    }

    /**
     * @inheritDoc
     */
    public function write($messages, string $type = null): OutputWriterInterface
    {
        $this->outputWriter->writeln(
            $this->validateMessages($messages)->validateMessageType($type)->formatMessages($messages, $type)
        );

        return $this;
    }

    /**
     * Formats messages by prefixing a timestamp and applying styling.
     *
     * @param string|string[] $messages
     * @param string $type
     *
     * @return string|string[]
     */
    private function formatMessages($messages, string $type = null)
    {
        if (is_string($messages)) {
            return is_null($type) ? $this->prefixTimestamp($messages)
                :  $this->prefixTimestamp("<$type>$messages</$type>");
        } else {
            foreach ($messages as &$message) {
                $message = is_null($type) ? $this->prefixTimestamp($message)
                    :  $this->prefixTimestamp("<$type>$message</$type>");
            }

            return $messages;
        }
    }
}