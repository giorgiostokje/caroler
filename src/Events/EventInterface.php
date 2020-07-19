<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events;

use GiorgioStokje\Caroler\Caroler;
use stdClass;

/**
 * Common Event interface
 *
 * All Events MUST implement this interface!
 *
 * @package GiorgioStokje\Caroler\Events
 * @see https://discord.com/developers/docs/topics/gateway#commands-and-events
 */
interface EventInterface
{
    /**
     * Prepares the Event.
     *
     * @param \stdClass|null $data
     *
     * @return \GiorgioStokje\Caroler\Events\EventInterface
     */
    public function prepare(?stdClass $data): EventInterface;

    /**
     * Executes the Event's logic.
     *
     * @param \GiorgioStokje\Caroler\Caroler $caroler
     *
     * @return \GiorgioStokje\Caroler\Events\EventInterface
     */
    public function handle(Caroler $caroler): EventInterface;
}
