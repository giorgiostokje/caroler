<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

/**
 * Heartbeat ACK Event handler class
 *
 * @package Caroler\EventHandlers
 */
class HeartbeatAck extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->setHeartbeatAcknowledged(true);
        $caroler->write("Heartbeat acknowledged by the Gateway.", true);

        return $this;
    }
}
