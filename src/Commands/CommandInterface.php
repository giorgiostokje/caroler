<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Caroler;
use Caroler\Objects\Message;

/**
 * Common command interface
 *
 * All commands must implement this interface!
 *
 * @package Caroler\Commands
 */
interface CommandInterface
{
    /**
     * Prepares the command.
     *
     * @param \Caroler\Objects\Message $message
     * @param \Caroler\Caroler $caroler
     *
     * @return bool
     */
    public function prepare(Message $message, Caroler $caroler): bool;

    /**
     * Validates provided logic before handling the command.
     *
     * @return bool "false" prevents further command handling.
     */
    public function validate(): bool;

    /**
     * Handles the command logic.
     *
     * @return bool
     */
    public function handle(): bool;

    /**
     * Returns the command signature.
     *
     * @return string
     */
    public function getSignature(): string;

    /**
     * Returns the command description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Returns the command author.
     *
     * @return string
     */
    public function getAuthor(): string;

    /**
     * Returns the command version.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Returns whether or not the command requires super administrator privileges.
     *
     * @return bool
     */
    public function requiresSuperAdmin(): bool;

    /**
     * Returns the command name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets the command name; derived from the command signature if null.
     *
     * @param string|null $name
     *
     * @return \Caroler\Commands\CommandInterface
     */
    public function setName(string $name = null): CommandInterface;

    /**
     * Returns the blueprint for the command's arguments.
     *
     * @return array
     */
    public function getArgumentsBlueprint(): array;

    /**
     * Returns the blueprint for the command's options.
     *
     * @return array
     */
    public function getOptionsBlueprint(): array;

    /**
     * Shows the command help dialog in the channel that triggered it.
     *
     * @return \Caroler\Objects\Message|null
     */
    public function showHelpDialog(): ?Message;
}
