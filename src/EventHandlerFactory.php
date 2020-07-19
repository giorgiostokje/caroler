<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler;

use GiorgioStokje\Caroler\EventHandlers\DispatchEvents\Ready;
use GiorgioStokje\Caroler\EventHandlers\EventHandlerInterface;
use GiorgioStokje\Caroler\EventHandlers\Heartbeat;
use GiorgioStokje\Caroler\EventHandlers\HeartbeatAck;
use GiorgioStokje\Caroler\EventHandlers\Identify;
use GiorgioStokje\Caroler\EventHandlers\NullEventHandler;
use stdClass;

/**
 * Event factory class
 *
 * @package GiorgioStokje\Caroler
 */
class EventHandlerFactory
{
    /**
     * Creates and prepares an Event based on the given payload.
     *
     * @param \stdClass $payload
     *
     * @return \GiorgioStokje\Caroler\EventHandlers\EventHandlerInterface
     */
    public static function make(stdClass $payload): EventHandlerInterface
    {
        $eventMap = [
            0 => [
                'READY' => function () {
                    return new Ready();
                }
            ],
            1 => function () {
                return new Heartbeat();
            },
//            7 => function () {
//
//            },
//            9 => function () {
//
//            },
            10 => function () {
                return new Identify();
            },
            11 => function () {
                return new HeartbeatAck();
            }
        ];

        $event = array_key_exists($payload->op, $eventMap)
            ? is_array($eventMap[$payload->op])
                ? array_key_exists($payload->t, $eventMap[$payload->op])
                    ? $eventMap[$payload->op][$payload->t]()
                    : new NullEventHandler()
                : $eventMap[$payload->op]()
            : new NullEventHandler();

        return $event->prepare($payload->d);
    }
}
