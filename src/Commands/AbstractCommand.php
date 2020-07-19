<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Commands;

use GiorgioStokje\Caroler\Caroler;
use GiorgioStokje\Caroler\Objects\Message;

/**
 * Common Command functionality
 *
 * @package GiorgioStokje\Caroler\Commands
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var \GiorgioStokje\Caroler\Objects\Message Message Object
     */
    protected $message;

    /**
     * @var \GiorgioStokje\Caroler\Caroler Application instance
     */
    protected $caroler;

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @inheritDoc
     */
    public function prepare(Message $message, Caroler $caroler): CommandInterface
    {
        $this->message = $message;
        $this->caroler = $caroler;

        return $this;
    }
}
