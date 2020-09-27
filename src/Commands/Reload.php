<?php

declare(strict_types=1);

namespace Caroler\Commands;

class Reload extends Command implements CommandInterface
{

    /**
     * @var string Command signature
     */
    protected $signature = 'reload';

    /**
     * @var string Command description
     */
    protected $description = 'Reloads the available commands';

    /**
     * @inheritDoc
     */
    public function handle(): bool
    {
        // TODO: implement

        return true;
    }
}
