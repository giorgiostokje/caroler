<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;
use stdClass;

/**
 * Unimplemented Event handler class
 *
 * @package Caroler\EventHandlers
 */
class NullEventHandler implements EventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(?stdClass $data): EventHandlerInterface
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
