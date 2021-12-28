<?php

declare(strict_types=1);

namespace Waldhacker\Hooky\DTO;

class HookableEventConfiguration
{
    public function __construct(
        public string $className,
        public string $label,
        public string $description,
    ) {
    }
}
