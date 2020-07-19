<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Events;

use GiorgioStokje\Caroler\Traits\Populatable;
use stdClass;

/**
 * Common Event functionality
 *
 * @package GiorgioStokje\Caroler\Events
 */
abstract class AbstractEvent implements EventInterface
{
    use Populatable;

    /**
     * @inheritDoc
     */
    public function prepare(?stdClass $data): EventInterface
    {
        $this->populate($data);

        return $this;
    }
}
