<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\Configuration;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use Waldhacker\Hooksie\DTO\HookConfiguration;

class HookConfigurationService
{

    public function __construct(protected QueryBuilder $queryBuilder)
    {
    }

    public function getConfiguredHooksByEvents(): array
    {
        $byEvent = [];
        $all = $this->queryBuilder->select('*')
            ->from('tx_hooksie_hook')
            ->execute()
            ->fetchAllAssociative();
        foreach ($all as $hook) {
            foreach (
                json_decode($hook['events'] ?? '{}', true, 512, JSON_THROW_ON_ERROR) as $event => $value
            ) {
                $byEvent[$event][] = new HookConfiguration(
                    $hook['url'],
                    $hook['secret']
                );
            }
        }
        return $byEvent;
    }
}
