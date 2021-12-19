<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\Listener;

class EventListener
{
    public function __invoke(\JsonSerializable $event): void
    {
        dd($event);
    }

}
