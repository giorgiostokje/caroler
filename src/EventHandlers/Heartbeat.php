<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

/**
 * Heartbeat Event Handler class
 *
 * @package Caroler\EventHandlers
 * @see https://discord.com/developers/docs/topics/gateway#heartbeat
 */
class Heartbeat extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        if ($caroler->getHeartbeatAcknowledged()) {
            $caroler->setHeartbeatAcknowledged(false);

            $caroler->getConnection()->send(json_encode([
                'op' => 1,
                'd' => $caroler->getSequence(),
            ]));

            $caroler->write("Heartbeat emitted by client.", true);
        } else {
            (new Resume())->handle($caroler);
        }

        return $this;
    }
}
