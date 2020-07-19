<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers;

use GiorgioStokje\Caroler\Caroler;

/**
 * Heartbeat Event (handler) class
 *
 * @package GiorgioStokje\Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#heartbeat
 */
class Heartbeat extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->connection->send(json_encode([
            'op' => 1,
            'd' => $caroler->sequence,
        ]));

        $caroler->write("Heartbeat emitted by client.", true);

        return $this;
    }
}