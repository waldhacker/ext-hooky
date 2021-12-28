<?php

declare(strict_types=1);

namespace Waldhacker\Hooky\Configuration;

use Waldhacker\Hooky\DTO\HookableEventConfiguration;

class HookableEvents
{
    protected array $events;

    public function __construct(array $eventConfiguration)
    {
        foreach ($eventConfiguration as $eventConfig) {
            $this->events[] = new HookableEventConfiguration(
                ...$eventConfig
            );
        }
    }

    public function get(): array
    {
        return $this->events;
    }
}
