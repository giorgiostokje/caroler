<?php

declare(strict_types=1);

namespace Caroler\Stores;

/**
 * Common Store interface
 *
 * All Store classes must implement this interface!
 *
 * @package Caroler\Stores
 */
interface StoreInterface
{
    /**
     * Retrieves an item from the store.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Stores an item in the store.
     *
     * @param string $key
     * @param $item
     *
     * @return \Caroler\Stores\StoreInterface
     */
    public function set(string $key, $item): StoreInterface;

    /**
     * Determines whether or not an item exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool;
}
