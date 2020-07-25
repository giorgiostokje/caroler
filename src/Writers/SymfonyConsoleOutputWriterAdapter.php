<?php

declare(strict_types=1);

namespace Caroler\Writers;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * SymfonyConsoleOutputWriterAdapter class
 *
 * @package Caroler\Writers
 */
class SymfonyConsoleOutputWriterAdapter extends AbstractWriter implements WriterInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface Symfony Console Output interface
     */
    private $writer;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $writer
     */
    public function __construct(OutputInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @inheritDoc
     */
    public function write($messages, string $type = null): WriterInterface
    {
        $this->writer->writeln(
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
