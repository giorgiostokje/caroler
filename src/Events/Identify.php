<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events;

use GiorgioStokje\Caroler\Caroler;

/**
 * Identify Event class
 *
 * @package GiorgioStokje\Caroler\Events
 * @see https://discord.com/developers/docs/topics/gateway#identify
 */
class Identify extends AbstractEvent implements EventInterface
{
    /**
     * @var int Heartbeat interval in milliseconds
     */
    protected $heartbeatInterval;

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventInterface
    {
        $caroler->loop->addPeriodicTimer($this->heartbeatInterval / 1000, function () use ($caroler) {
            (new Heartbeat())->handle($caroler);
        });

        $caroler->write("Emitting heartbeat every " . round($this->heartbeatInterval / 1000, 2) . " seconds.");

        $caroler->connection->send(json_encode([
            'op' => 2,
            'd' => [
                'token' => $caroler->token,
                'properties' => [
                    '$os' => 'Linux',
                    '$browser' => 'Caroler',
                    '$device' => 'Caroler',
                ]
            ]
        ]));

        $caroler->write("Identified with the Gateway.");

        return $this;
    }
}
