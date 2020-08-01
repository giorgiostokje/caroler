<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Caroler;
use Caroler\Objects\Message;

/**
 * Common Command interface
 *
 * All Commands must implement this interface!
 *
 * @package Caroler\Commands
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
     * @param \Caroler\Objects\Message $message
     * @param \Caroler\Caroler $caroler
     *
     * @return \Caroler\Commands\CommandInterface
     */
    public function prepare(Message $message, Caroler $caroler): CommandInterface;

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function handle(): bool;
}
