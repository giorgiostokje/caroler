<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Objects;

use stdClass;

/**
 * Common object interface
 * All Objects MUST implement this interface!
 *
 * @package GiorgioStokje\Caroler\Objects
 */
interface ObjectInterface
{
    /**
     * Prepares the Object.
     *
     * @param \stdClass $data
     *
     * @return \GiorgioStokje\Caroler\Objects\ObjectInterface
     */
    public function prepare(stdClass $data): ObjectInterface;
}
