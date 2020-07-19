<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Commands;

use GiorgioStokje\Caroler\Caroler;
use GiorgioStokje\Caroler\Objects\Message;

/**
 * Common Command interface
 *
 * @package GiorgioStokje\Caroler\Commands
 */
interface CommandInterface
{
    /**
     * Returns the Command signature.
     *
     * @return string
     */
    public function getSignature(): string;

    /**
     * Prepares the command before execution.
     *
     * @param \GiorgioStokje\Caroler\Objects\Message $message
     * @param \GiorgioStokje\Caroler\Caroler $caroler
     *
     * @return \GiorgioStokje\Caroler\Commands\CommandInterface
     */
    public function prepare(Message $message, Caroler $caroler): CommandInterface;

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute(): bool;
}
