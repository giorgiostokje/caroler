<?php

declare(strict_types=1);

namespace Caroler\Objects;

use Caroler\Traits\Populatable;
use stdClass;

/**
 * Common Object functionality
 *
 * @package Caroler\Objects
 */
abstract class AbstractObject implements ObjectInterface
{
    use Populatable;

    /**
     * @inheritDoc
     */
    public function prepare(stdClass $data): ObjectInterface
    {
        $this->populate($data);

        return $this;
    }
}
