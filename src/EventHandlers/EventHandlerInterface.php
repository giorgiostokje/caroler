<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;
use stdClass;

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
     * @param \stdClass|null $data
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public function prepare(?stdClass $data): EventHandlerInterface;

    /**
     * Executes the Event Handler's logic.
     *
     * @param \Caroler\Caroler $caroler
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public function handle(Caroler $caroler): EventHandlerInterface;
}
