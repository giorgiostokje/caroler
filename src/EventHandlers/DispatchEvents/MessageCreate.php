<?php

declare(strict_types=1);

namespace Caroler\EventHandlers\DispatchEvents;

use Caroler\Caroler;
use Caroler\EventHandlers\AbstractEventHandler;
use Caroler\EventHandlers\EventHandlerInterface;
use Caroler\Objects\Message;
use stdClass;

/**
 * MESSAGE_CREATE event handler class
 *
 * @package Caroler\EventHandlers\DispatchEvents
 * @see https://discord.com/developers/docs/topics/gateway#message-create
 */
class MessageCreate extends AbstractEventHandler implements EventHandlerInterface
{
    /**
     * @var \Caroler\Objects\Message Created Message object
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
        if (
            (isset($this->message->author->bot) && $this->message->author->bot)
            || (isset($this->message->author->system) && $this->message->author->system)
        ) {
            return $this;
        }

        if (
            substr(
                $this->message->content,
                0,
                strlen($caroler->getOption('command_prefix'))
            ) === $caroler->getOption('command_prefix')
        ) {
            $signature = substr(
                strtok($this->message->content, ' '),
                strlen($caroler->getOption('command_prefix')),
                strlen(strtok($this->message->content, ' ')) - strlen($caroler->getOption('command_prefix'))
            );
        }

        if (isset($signature) && $caroler->commandExists($signature)) {
            $command = $caroler->getCommand($signature);
            /** @var \Caroler\Commands\CommandInterface $command */
            $command = new $command();
            $command->prepare($this->message, $caroler)->handle();
        }

        return $this;
    }
}
