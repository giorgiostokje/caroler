<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Caroler;

/**
 * Common Resource interface
 *
 * All Resource classes must implement this interface!
 *
 * @package Caroler\Resources
 */
interface ResourceInterface
{
    /**
     * Prepares the Resource.
     *
     * @param string|\Caroler\Objects\Message $context
     * @param \Caroler\Caroler $caroler
     *
     * @return \Caroler\Resources\ResourceInterface
     */
    public function prepare($context, Caroler $caroler): ResourceInterface;
}
