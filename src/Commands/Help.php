<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Objects\Embed;

class Help extends Command
{
    protected $signature = 'help {command? : Command to display help for}';
    protected $description = 'Displays a list of available commands.';
    protected $author = '137568507096203264';
    protected $version = '0.1.0';

    /**
     * @inheritDoc
     * @throws \OutOfBoundsException
     */
    public function handle(): bool
    {
        $cmdPrefix = $this->caroler()->getConfig('command_prefix');

        if (!is_null($this->argument('command'))) {
            $command = $this->caroler()->getCommand($this->argument('command'));

            if (!is_null($command)) {
                $command = new $command();
                $command->prepare($this->message(), $this->caroler());
                $command->showHelpDialog();
            }

            unset($command);
        } else {
            $this->embed()->setTitle("Available Commands")
                ->setDescription("Type **{$cmdPrefix}help _command_** to view detailed command help.")
                ->setColor(Embed::COLOR_DISCORD);
            $commands = $this->caroler()->getCommands();
            ksort($commands);

            foreach ($commands as $name => $class) {
                $command = new $class();
                $this->embed()->addField(
                    $cmdPrefix . $name,
                    $command->getDescription()
                );
                unset($command);
            }

            $this->resource('channel')->createMessage('', $this->embed());
        }

        return true;
    }
}
