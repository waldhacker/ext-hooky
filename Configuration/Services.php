<?php

declare(strict_types = 1);

namespace TYPO3\CMS\Dashboard;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Waldhacker\Hooky\DependencyInjection\HookablePass;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->addCompilerPass(
        new HookablePass('hooky.hookable'),
        PassConfig::TYPE_BEFORE_OPTIMIZATION,
        10
    );
};
