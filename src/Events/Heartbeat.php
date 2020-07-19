<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events;

use GiorgioStokje\Caroler\Caroler;

/**
 * Heartbeat Event (handler) class
 *
 * @package GiorgioStokje\Caroler\Events
 * @see https://discord.com/developers/docs/topics/gateway#heartbeat
 */
class Heartbeat extends AbstractEvent implements EventInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventInterface
    {
        $caroler->connection->send(json_encode([
            'op' => 1,
            'd' => $caroler->sequence,
        ]));

        $caroler->write("Heartbeat emitted.", true);

        return $this;
    }
}
