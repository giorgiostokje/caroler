<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Objects;

use GiorgioStokje\Caroler\Traits\Populatable;
use stdClass;

/**
 * Common Object functionality
 *
 * @package GiorgioStokje\Caroler\Objects
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
