<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\Configuration;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use Waldhacker\Hooky\DTO\HookConfiguration;

class HookConfigurationService
{

    public function __construct(protected QueryBuilder $queryBuilder)
    {
    }

    public function getConfiguredHooksByEvents(): array
    {
        $byEvent = [];
        $all = $this->queryBuilder->select('*')
            ->from('tx_hooky_hook')
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
