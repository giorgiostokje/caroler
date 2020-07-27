<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Objects\Embed;

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

        $this->caroler->send("Test", $this->message, $embed);

        return true;
    }
}
