<?php

declare(strict_types=1);

namespace Waldhacker\Hooky\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Waldhacker\Hooky\Configuration\HookableEvents;
use Waldhacker\Hooky\Listener\EventListener;

class HookablePass implements CompilerPassInterface
{
    public function __construct(protected string $tag)
    {
    }

    public function process(ContainerBuilder $container): void
    {
        $hookableEvents = [];
        $definition = $container->getDefinition(EventListener::class);
        foreach ($container->findTaggedServiceIds($this->tag) as $serviceName => $tags) {
            $definition->addTag('event.listener', [
                'event' => $serviceName,
                'identifier' => $serviceName
            ]);
            foreach ($tags as $attributes) {
                $hookableEvents[] = [
                    $serviceName,
                    $attributes['label'] ?? $serviceName,
                    $attributes['description'] ?? ''
                ];
            }
        }
        $container->setDefinition(EventListener::class, $definition);
        $container->getDefinition(HookableEvents::class)
            ->addArgument($hookableEvents)->setPublic(true);
    }
}
