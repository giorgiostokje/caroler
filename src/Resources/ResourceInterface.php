<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Caroler;

interface ResourceInterface
{
    public function prepare($context, Caroler $caroler): ResourceInterface;
}
