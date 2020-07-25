<?php

declare(strict_types=1);

namespace Caroler\EventHandlers;

use Caroler\Caroler;

class Reconnect extends AbstractEventHandler implements EventHandlerInterface
{
    public function handle(Caroler $caroler): EventHandlerInterface
    {
        $caroler->conclude()->sing();

        return $this;
    }
}
