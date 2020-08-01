<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Caroler;
use Caroler\Objects\Embed;
use Caroler\Objects\Message;
use Caroler\Resources\Channel;

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
     * @var string Command author
     */
    protected $author;

    /**
     * @var string Command version – SemVer is recommended!
     */
    protected $version;

    /**
     * @var \Caroler\Objects\Message Message Object
     */
    protected $message;

    /**
     * @var \Caroler\Caroler Application instance
     */
    protected $caroler;

    /**
     * @var \Caroler\Resources\Channel Discord Channel REST API resource
     */
    protected $channel;

    /**
     * @var \Caroler\Objects\Embed Discord Rich Embed
     */
    protected $embed;

    /**
     * @inheritDoc
     */
    public function prepare(Message $message, Caroler $caroler): CommandInterface
    {
        $this->message = $message;
        $this->caroler = $caroler;
        $this->channel = new Channel();
        $this->embed = new Embed();

        return $this;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
