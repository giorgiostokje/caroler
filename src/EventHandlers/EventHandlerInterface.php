<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;
use stdClass;

/**
 * Common Event interface
 *
 * All EventHandlers MUST implement this interface!
 *
 * @package Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#commands-and-events
 */
interface EventHandlerInterface
{
    /**
     * Prepares the Event.
     *
     * @param \stdClass|null $data
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public function prepare(?stdClass $data): EventHandlerInterface;

    /**
     * Executes the Event's logic.
     *
     * @param \Caroler\Caroler $caroler
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public function handle(Caroler $caroler): EventHandlerInterface;
}
