<?php

declare(strict_types=1);

namespace App\Caroler\Commands;

use GiorgioStokje\Caroler\Commands\Command;

/**
 * Dice Command class
 *
 * @package GiorgioStokje\Caroler\Examples
 */
class Dice extends Command
{
    /**
     * @var string Command signature
     */
    protected $signature = 'dice';

    /**
     * @var string Command description
     */
    protected $description = 'Rolls a dice.';

    /**
     * @inheritDoc
     */
    public function handle(): bool
    {
        $number = rand(1, 6);

        $this->caroler->send(":game_die: You rolled a **$number**!", $this->message);

        return true;
    }
}
