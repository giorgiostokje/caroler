<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\EventHandlers;

use GiorgioStokje\Caroler\Caroler;

class Reconnect extends AbstractEventHandler implements EventHandlerInterface
{
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->conclude()->sing();

        return $this;
    }
}
