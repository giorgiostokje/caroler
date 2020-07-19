<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers;

use GiorgioStokje\Caroler\Caroler;
use stdClass;

/**
 * Common Event interface
 *
 * All EventHandlers MUST implement this interface!
 *
 * @package GiorgioStokje\Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#commands-and-events
 */
interface EventHandlerInterface
{
    /**
     * Prepares the Event.
     *
     * @param \stdClass|null $data
     *
     * @return \GiorgioStokje\Caroler\EventHandlers\EventHandlerInterface
     */
    public function prepare(?stdClass $data): EventHandlerInterface;

    /**
     * Executes the Event's logic.
     *
     * @param \GiorgioStokje\Caroler\Caroler $caroler
     *
     * @return \GiorgioStokje\Caroler\EventHandlers\EventHandlerInterface
     */
    public function handle(Caroler $caroler): EventHandlerInterface;
}
