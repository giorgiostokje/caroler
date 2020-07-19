<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events;

use GiorgioStokje\Caroler\Caroler;
use stdClass;

/**
 * Unimplemented Event handler class
 *
 * @package GiorgioStokje\Caroler\Events
 */
class NullEvent implements EventInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(?stdClass $data): EventInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventInterface
    {
        return $this;
    }
}
