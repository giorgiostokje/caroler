<?php

declare(strict_types=1);

namespace Caroler\Stores;

/**
 * Common Store functionality
 *
 * @package Caroler\Stores
 */
abstract class AbstractStore implements StoreInterface
{
    /**
     * @var array Items in the store
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function get(string $key = null)
    {
        return is_null($key) ? $this->items : $this->items[$key] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $item): StoreInterface
    {
        $this->items[$key] = $item;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return isset($this->items[$key]);
    }
}
