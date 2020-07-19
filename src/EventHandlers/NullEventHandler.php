<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers;

use GiorgioStokje\Caroler\Caroler;
use stdClass;

/**
 * Unimplemented Event handler class
 *
 * @package GiorgioStokje\Caroler\EventHandlers
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
