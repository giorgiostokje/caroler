<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

/**
 * Resume Event handler class
 *
 * @package Caroler\EventHandlers
 */
class Resume extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->write("Connection to the Gateway was lost. An attempt to reconnect and resume will be made.");

        $caroler->conclude()->sing();

        sleep(rand(1, 5));

        $caroler->getConnection()->send(json_encode([
            'op' => 6,
            'd' => [
                'token' => $caroler->getToken(),
                'session_id' => $caroler->getState()->getSessionId(),
                'seq' => $caroler->getSequence()
            ]
        ]));

        return $this;
    }
}
