<?php

declare(strict_types=1);

namespace Caroler\Exceptions;

/**
 * General application exception interface
 *
 * @package Caroler\Exceptions
 */
interface CarolerException
{
    /**
     * Retrieves the context in which the exception occurred.
     *
     * @return array
     */
    public function getContext(): array;
}
