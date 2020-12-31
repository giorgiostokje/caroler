<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

/**
 * Unimplemented Event Handler class
 *
 * @package Caroler\EventHandlers
 */
class NullEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(?array $data): EventHandlerInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        return $this;
    }
}
