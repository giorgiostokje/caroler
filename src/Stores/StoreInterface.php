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
     * Retrieves one or all items from the Store.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function get(string $key = null);

    /**
     * Stores an item in the Store.
     *
     * @param string $key
     * @param $item
     *
     * @return \Caroler\Stores\StoreInterface
     */
    public function set(string $key, $item): StoreInterface;

    /**
     * Determines whether or not an item exists in the Store.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool;
}
