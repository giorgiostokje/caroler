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
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function handle(): bool
    {
        $this->embed->setTitle("Available Commands")->setColor(Embed::COLOR_DISCORD);
        $commands = $this->caroler->getCommands();
        ksort($commands);

        foreach ($commands as $signature => $class) {
            $command = new $class();
            $this->embed->addField(
                $this->caroler->getOption('command_prefix') . $signature,
                $command->getDescription()
            );
            unset($command);
        }

        $this->channel->prepare($this->message, $this->caroler)->createMessage([
            'content' => "",
            'embed' => $this->embed
        ]);

        return true;
    }
}
