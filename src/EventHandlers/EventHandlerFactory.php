<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\EventHandlers\DispatchEvents\MessageCreate;
use Caroler\EventHandlers\DispatchEvents\Ready;
use stdClass;

/**
 * Event Handler factory class
 *
 * @package Caroler\EventHandlers
 */
class EventHandlerFactory
{
    /**
     * Creates and prepares an Event Handler based on the given payload.
     *
     * @param \stdClass $payload
     *
     * @return \Caroler\EventHandlers\EventHandlerInterface
     */
    public static function make(stdClass $payload): EventHandlerInterface
    {
        $eventMap = [
            0 => [
                'READY' => function () {
                    return new Ready();
                },
                'MESSAGE_CREATE' => function () {
                    return new MessageCreate();
                },
            ],
            1 => function () {
                return new Heartbeat();
            },
            7 => function () {
                return new Reconnect();
            },
            9 => function () {
                return new Identify();
            },
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
