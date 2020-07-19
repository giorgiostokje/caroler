<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Commands;

/**
 * Dice Command class
 *
 * @package GiorgioStokje\Caroler\Commands
 */
class Dice extends AbstractCommand implements CommandInterface
{
    /**
     * @var string Command signature
     */
    protected $signature = 'dice';

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        $number = rand(1, 6);

        $this->caroler->send(":game_die: You rolled a **$number**!", $this->message);

        return true;
    }
}
