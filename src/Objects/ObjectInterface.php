<?php

declare(strict_types=1);

namespace Caroler\Objects;

use stdClass;

/**
 * Common (Discord) object interface
 * All Objects must implement this interface!
 *
 * @package Caroler\Objects
 */
interface ObjectInterface
{
    /**
     * Prepares the object.
     *
     * @param \stdClass|array $data
     *
     * @return \Caroler\Objects\ObjectInterface
     */
    public function prepare($data): ObjectInterface;

    /**
     * Returns all non-null object properties as an array.
     *
     * @return array
     **/
    public function toArray(): array;
}
