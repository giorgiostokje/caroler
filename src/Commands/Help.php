<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Objects\Embed;
use Caroler\Resources\Channel;

class Help extends Command
{
    /**
     * @var string Command signature
     */
    protected $signature = 'help';

    /**
     * @var string Command description
     */
    protected $description = 'Displays a list of available commands.';

    /**
     * @inheritDoc
     */
    public function handle(): bool
    {
        $embed = new Embed();
        $embed->setTitle("Available Commands")->setColor(Embed::COLOR_GREEN);
        $commands = $this->caroler->getCommands();
        ksort($commands);

        foreach ($commands as $signature => $class) {
            $command = new $class();
            $embed->addField($this->caroler->getOption('command_prefix') . $signature, $command->getDescription());
            unset($command);
        }

        $channel = new Channel();
        $channel->prepare($this->message, $this->caroler)->createMessage([
            'content' => "Test",
            'embed' => $embed
        ]);

        return true;
    }
}
