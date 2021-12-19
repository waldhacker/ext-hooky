<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\DataHandler;

use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use Waldhacker\Hooksie\Events\RecordCreatedEvent;
use Waldhacker\Hooksie\Events\RecordUpdatedEvent;

class PostProcessor
{
    public function __construct(protected EventDispatcherInterface $dispatcher)
    {

    }

    public function processDatamap_afterDatabaseOperations(string $status, string $table, $id, array $fieldArray, DataHandler $datahandler): void
    {
        switch ($status) {
            case 'new':
                if (str_starts_with($id, 'NEW')) {
                    $id = $datahandler->substNEWwithIDs[$id];
                }
                $this->dispatcher->dispatch(new RecordCreatedEvent($table, $id, $fieldArray));
                break;
            case 'update':
                $this->dispatcher->dispatch(new RecordUpdatedEvent($table, $id, $fieldArray));
                break;
        }
    }

}
