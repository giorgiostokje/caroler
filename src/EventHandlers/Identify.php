<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;
use React\EventLoop\Timer\Timer;

/**
 * Identify Event Handler class
 *
 * @package Caroler\EventHandlers
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
        $caroler->setHeartbeatAcknowledged(true);

        $currTimer = $caroler->getHeartbeatTimer();
        if (isset($currTimer)) {
            $caroler->setHeartbeatTimer(null);
        }

        $caroler->getEventLoop()
            ->addPeriodicTimer($this->heartbeatInterval / 1000, function (Timer $timer) use ($caroler) {
                $caroler->setHeartbeatTimer($timer);

                (new Heartbeat())->handle($caroler);
            });

        $caroler->write("Emitting heartbeat to the Gateway every "
            . round($this->heartbeatInterval / 1000, 2) . " seconds.");

        $caroler->getConnection()->send(json_encode([
            'op' => 2,
            'd' => [
                'token' => $caroler->getOption('token'),
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
