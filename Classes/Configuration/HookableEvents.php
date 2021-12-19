<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\Configuration;

use Waldhacker\Hooksie\DTO\HookableEventConfiguration;

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
