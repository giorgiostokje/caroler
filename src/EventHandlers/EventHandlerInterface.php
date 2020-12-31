<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

/**
 * Common Event Handler interface
 *
 * All Event Handlers must implement this interface!
 *
 * @package Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#commands-and-events
 */
interface EventHandlerInterface
{
    /**
     * Prepares the Event Handler.
     *
     * @param array|null $data
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public function prepare(?array $data): EventHandlerInterface;

    /**
     * Executes the Event Handler's logic.
     *
     * @param \Caroler\Caroler $caroler
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public function handle(Caroler $caroler): EventHandlerInterface;
}
