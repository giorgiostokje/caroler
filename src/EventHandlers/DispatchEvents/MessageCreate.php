<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers\DispatchEvents;

use GiorgioStokje\Caroler\Caroler;
use GiorgioStokje\Caroler\EventHandlers\AbstractEventHandler;
use GiorgioStokje\Caroler\EventHandlers\EventHandlerInterface;
use GiorgioStokje\Caroler\Objects\Message;
use stdClass;

/**
 * Message Create Event handler class
 *
 * @package GiorgioStokje\Caroler\EventHandlers\DispatchEvents
 * @see https://discord.com/developers/docs/topics/gateway#message-create
 */
class MessageCreate extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @var \GiorgioStokje\Caroler\Objects\Message Created Message object
     */
    private $message;

    public function __construct()
    {
        $this->message = new Message();
    }

    /**
     * @inheritDoc
     */
    public function prepare(?stdClass $data): EventHandlerInterface
    {
        $this->message->prepare($data);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        if (substr($this->message->content, 0, strlen($caroler->getCommandPrefix())) === $caroler->getCommandPrefix()) {
            $cmd = substr(
                strtok($this->message->content, ' '),
                strlen($caroler->getCommandPrefix()),
                strlen(strtok($this->message->content, ' ')) - strlen($caroler->getCommandPrefix())
            );
        }

        if (isset($cmd) && isset($caroler->getCommands()[$cmd])) {
            $command = $caroler->getCommands()[$cmd];
            /** @var \GiorgioStokje\Caroler\Commands\CommandInterface $command */
            $command = new $command();
            $command->prepare($this->message, $caroler)->execute();
        }

        return $this;
    }
}
