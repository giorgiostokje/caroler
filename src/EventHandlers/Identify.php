<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers;

use GiorgioStokje\Caroler\Caroler;

/**
 * Identify Event class
 *
 * @package GiorgioStokje\Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#identify
 */
class Identify extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @var int Heartbeat interval in milliseconds
     */
    protected $heartbeatInterval;

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->getLoop()->addPeriodicTimer($this->heartbeatInterval / 1000, function () use ($caroler) {
            (new Heartbeat())->handle($caroler);
        });

        $caroler->write("Emitting heartbeat to the Gateway every "
            . round($this->heartbeatInterval / 1000, 2) . " seconds.");

        $caroler->getConnection()->send(json_encode([
            'op' => 2,
            'd' => [
                'token' => $caroler->getToken(),
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
