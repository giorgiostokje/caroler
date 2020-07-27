<?php

declare(strict_types=1);

namespace Caroler\Commands;

/**
 * About command class
 *
 * @package Caroler\Commands
 */
class About extends Command
{
    /**
     * @var string Command signature
     */
    protected $signature = 'about';

    /**
     * @var string Command description
     */
    protected $description = 'Displays information about the Caroler Discord bot.';

    /**
     * @inheritDoc
     */
    public function handle(): bool
    {
        $this->caroler->send(
            "Caroler – An extensible Discord bot written in PHP\n"
            . "Developed by <@137568507096203264>\n"
            . "Read the documentation at https://carolerbot.com\n"
            . "Join the official Discord server at https://discord.gg/6m5sjTW",
            $this->message
        );

        return true;
    }
}
