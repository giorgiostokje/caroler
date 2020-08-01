<?php

declare(strict_types=1);

namespace Caroler\Objects;

use Caroler\Traits\Arrayable;
use Caroler\Traits\Populatable;

/**
 * Common (Discord) Object functionality
 *
 * @package Caroler\Objects
 */
abstract class AbstractObject implements ObjectInterface
{
    use Arrayable;
    use Populatable;

    /**
     * @inheritDoc
     */
    public function prepare($data): ObjectInterface
    {
        $this->populate($data);

        return $this;
    }
}
