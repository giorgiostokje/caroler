<?php

declare(strict_types=1);

namespace Caroler\Exceptions;

use RuntimeException;

class IOException extends RuntimeException implements CarolerException
{
    /**
     * @var array Context in which the exception occurred
     */
    private $context;

    public function __construct($message = "", array $context = [])
    {
        $this->context = $context;

        parent::__construct($message);
    }

    /**
     * @inheritDoc
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
