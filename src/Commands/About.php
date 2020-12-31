<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Caroler;
use Caroler\Objects\Embed;

/**
 * About command class
 *
 * @package Caroler\Commands
 */
class About extends Command
{
    protected $signature = 'about';
    protected $description = 'Displays information about the Caroler Discord bot.';
    protected $author = '137568507096203264';
    protected $version = '1.0.0';

    /**
     * @inheritDoc
     * @throws \OutOfBoundsException
     */
    public function handle(): bool
    {
        $this->resource('channel')->createMessage(
            null,
            $this->embed()->setTitle('Caroler')
                ->setDescription('An extensible Discord bot written in PHP.')
                ->setUrl('https://carolerbot.com')
                ->setColor(Embed::COLOR_DISCORD)
                ->setImage('https://i.imgur.com/Oo0qogl.png')
                ->addField('Website', 'https://carolerbot.com')
                ->addField('Discord Server', 'https://discord.gg/6m5sjTW')
                ->addField('Version', Caroler::APP_VERSION . "\nhttps://github.com/giorgiostokje/caroler")
                ->addField('Developer', '<@137568507096203264>')
        );

        return true;
    }
}
