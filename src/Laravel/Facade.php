<?php

declare(strict_types=1);

namespace Caroler\Laravel;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Facade
 *
 * @package Caroler\Laravel
 */
class Facade extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'caroler';
    }
}
