<?php

declare(strict_types=1);

namespace Caroler\EventHandlers\DispatchEvents;

use Caroler\Caroler;
use Caroler\EventHandlers\AbstractEventHandler;
use Caroler\EventHandlers\EventHandlerInterface;
use Caroler\Objects\Message;

/**
 * MESSAGE_CREATE Dispatch Event Handler class
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
    public function prepare(?array $data): EventHandlerInterface
    {
        $this->message->prepare($data);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        if ($this->message->getAuthor()->isBot() || $this->message->getAuthor()->isSystem()) {
            return $this;
        }

        if (
            substr(
                $this->message->getContent(),
                0,
                strlen($caroler->getConfig('command_prefix'))
            ) === $caroler->getConfig('command_prefix')
        ) {
            $name = substr(
                strtok($this->message->getContent(), ' '),
                strlen($caroler->getConfig('command_prefix')),
                strlen(strtok($this->message->getContent(), ' '))
                    - strlen($caroler->getConfig('command_prefix'))
            );
        }

        if (isset($name) && $caroler->commandExists($name)) {
            $command = $caroler->getCommand($name);
            /** @var \Caroler\Commands\CommandInterface $command */
            $command = new $command();
        }

        if (
            isset($command)
            && (
                ($command->requiresSuperAdmin()
                    && $this->message->getAuthor()->getId() === $caroler->getConfig('admin_id'))
                || !$command->requiresSuperAdmin()
            )
            && $command->prepare($this->message, $caroler)
            && $command->validate()
        ) {
            $command->handle();
        }

        return $this;
    }
}
