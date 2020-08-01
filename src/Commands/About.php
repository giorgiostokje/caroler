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
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function handle(): bool
    {
        $this->channel->prepare($this->message, $this->caroler)->createMessage([
            'content' => "",
            'embed' => $this->embed->setTitle('Caroler')
                ->setDescription('An extensible Discord bot written in PHP.')
                ->setUrl('https://carolerbot.com')
                ->setColor(Embed::COLOR_BLUE)
                ->setImage('https://i.imgur.com/cV2Q60v.png', 256, 128)
                ->setThumbnail('https://i.imgur.com/Bzs43J5.png')
                ->addField('Website', 'https://carolerbot.com')
                ->addField('Discord Server', 'https://discord.gg/6m5sjTW')
                ->addField('Version', Caroler::APP_VERSION . "\nhttps://github.com/giorgiostokje/caroler/tree/develop")
                ->addField('Developer', '<@137568507096203264>')
        ]);

        return true;
    }
}
