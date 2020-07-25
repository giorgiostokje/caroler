<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Caroler;
use Caroler\Objects\Message;

/**
 * Common Command functionality
 *
 * @package Caroler\Commands
 */
abstract class Command implements CommandInterface
{
    /**
     * @var string Command signature
     */
    protected $signature;

    /**
     * @var string Command description
     */
    protected $description;

    /**
     * @var \Caroler\Objects\Message Message Object
     */
    protected $message;

    /**
     * @var \Caroler\Caroler Application instance
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
