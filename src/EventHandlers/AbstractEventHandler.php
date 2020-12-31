<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Traits\Populatable;

/**
 * Common Event Handler functionality
 *
 * @package Caroler\EventHandlers
 */
abstract class AbstractEventHandler implements EventHandlerInterface
{
    use Populatable;

    /**
     * @inheritDoc
     */
    public function prepare(?array $data): EventHandlerInterface
    {
        $this->populate($data);

        return $this;
    }
}
