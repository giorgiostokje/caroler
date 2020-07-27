<?php

declare(strict_types=1);

namespace Caroler\Objects;

use stdClass;

/**
 * Common object interface
 * All Objects MUST implement this interface!
 *
 * @package Caroler\Objects
 */
interface ObjectInterface
{
    /**
     * Prepares the Object.
     *
     * @param \stdClass $data
     *
     * @return \Caroler\Objects\ObjectInterface
     */
    public function prepare(stdClass $data): ObjectInterface;

    public function toArray(): array;
}
