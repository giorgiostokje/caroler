<?php

declare(strict_types=1);

namespace Caroler\Writers;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * WriterFactory class
 *
 * @package Caroler\Writers
 */
class WriterFactory
{
    /**
     * Returns a Writer object, based on the provided parameter.
     *
     * @param string|\Caroler\Writers\WriterInterface|\Symfony\Component\Console\Output\OutputInterface $writer
     *
     * @return \Caroler\Writers\WriterInterface
     */
    public static function make($writer): WriterInterface
    {
        if ($writer instanceof OutputInterface) {
            return new SymfonyConsoleOutputWriterAdapter($writer);
        } elseif ($writer instanceof WriterInterface) {
            return $writer;
        }

        switch ($writer) {
            case 'console':
                return new ConsoleWriter();
            default:
                throw new \InvalidArgumentException("Invalid writer provided!");
        }
    }
}
