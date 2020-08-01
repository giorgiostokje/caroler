<?php

declare(strict_types=1);

namespace Caroler\OutputWriters;

use Caroler\Exceptions\InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Output Writer factory class
 *
 * @package Caroler\OutputWriters
 */
class OutputWriterFactory
{
    /**
     * Returns an OutputWriter object, based on the provided parameter.
     *
     * @param string|OutputWriterInterface|\Symfony\Component\Console\Output\OutputInterface $outputWriter
     *
     * @return \Caroler\OutputWriters\OutputWriterInterface
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public static function make($outputWriter): OutputWriterInterface
    {
        if ($outputWriter instanceof OutputInterface) {
            return new SymfonyConsoleOutputWriterAdapter($outputWriter);
        } elseif ($outputWriter instanceof OutputWriterInterface) {
            return $outputWriter;
        }

        switch ($outputWriter) {
            case 'console':
                return new ConsoleOutputWriter();
            default:
                throw new InvalidArgumentException("Invalid output writer provided!");
        }
    }
}
