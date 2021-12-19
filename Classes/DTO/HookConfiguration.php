<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\DTO;

class HookConfiguration
{
    public function __construct(
        protected string $url,
        protected string $secret
    )
    {

    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

}
