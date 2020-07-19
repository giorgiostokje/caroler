<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events;

use GiorgioStokje\Caroler\Caroler;

/**
 * Heartbeat ACK Event handler class
 *
 * @package GiorgioStokje\Caroler\Events
 */
class HeartbeatAck extends AbstractEvent implements EventInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventInterface
    {
        $caroler->write("Heartbeat acknowledged.", true);

        return $this;
    }
}
