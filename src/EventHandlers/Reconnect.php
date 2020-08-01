<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

/**
 * Reconnect Event Handler class
 *
 * @package Caroler\EventHandlers
 */
class Reconnect extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->conclude()->sing();

        return $this;
    }
}
