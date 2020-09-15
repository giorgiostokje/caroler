<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Common (Discord) object interface
 *
 * All objects must implement this interface!
 *
 * @package Caroler\Objects
 */
interface ObjectInterface
{
    /**
     * Prepares the object.
     *
     * @param array $data
     *
     * @return \Caroler\Objects\ObjectInterface
     */
    public function prepare(array $data): ObjectInterface;

    /**
     * Transforms each array entry to the specified object.
     *
     * @param array $data
     * @param string $object
     *
     * @return array
     */
    public function transform(array $data, string $object): array;

    /**
     * Returns all non-null object properties as an array.
     *
     * @return array
     **/
    public function toArray(): array;
}
