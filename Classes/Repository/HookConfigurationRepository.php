<?php

declare(strict_types=1);

namespace Waldhacker\Hooky\Repository;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Waldhacker\Hooky\DTO\HookConfiguration;

class HookConfigurationRepository
{
    public function __construct(protected QueryBuilder $queryBuilder)
    {
    }

    public function getConfiguredHooksByEvents(): array
    {
        $byEvent = [];
        /** @var array $all */
        $all = $this->queryBuilder->select('*')
            ->from('tx_hooky_hook')
            ->execute()
            ->fetchAllAssociative();
        foreach ($all as $hook) {
            $hookEvents = json_decode($hook['events'] ?? '{}', true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($hookEvents)) {
                continue;
            }
            foreach (
                $hookEvents as $event => $value
            ) {
                $byEvent[$event][] = new HookConfiguration(
                    $hook['url'],
                    $hook['secret']
                );
            }
        }
        return $byEvent;
    }

    public function fetchAll(): array
    {
        $qb = clone $this->queryBuilder;
        $qb
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        return $qb->select('*')
            ->from('tx_hooky_hook')
            ->execute()
            ->fetchAllAssociative();
    }
}
