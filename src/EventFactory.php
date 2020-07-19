<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler;

use GiorgioStokje\Caroler\Events\DispatchEvents\Ready;
use GiorgioStokje\Caroler\Events\EventInterface;
use GiorgioStokje\Caroler\Events\Heartbeat;
use GiorgioStokje\Caroler\Events\HeartbeatAck;
use GiorgioStokje\Caroler\Events\Identify;
use GiorgioStokje\Caroler\Events\NullEvent;
use stdClass;

/**
 * Event factory class
 *
 * @package GiorgioStokje\Caroler
 */
class EventFactory
{
    /**
     * Creates and prepares an Event based on the given payload.
     *
     * @param \stdClass $payload
     *
     * @return \GiorgioStokje\Caroler\Events\EventInterface
     */
    public static function make(stdClass $payload): EventInterface
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
                    : new NullEvent()
                : $eventMap[$payload->op]()
            : new NullEvent();

        return $event->prepare($payload->d);
    }
}
