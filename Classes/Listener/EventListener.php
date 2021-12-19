<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\Listener;

use Enqueue\Dbal\DbalContext;
use JsonSerializable;
use TYPO3\CMS\Core\Database\ConnectionPool;

class EventListener
{
    public function __construct(protected ConnectionPool $connectionPool)
    {

    }

    public function __invoke(JsonSerializable $event): void
    {
        $connection = $this->connectionPool
            ->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
        $context = new DbalContext($connection);
        $queue = $context->createQueue('events');
        $body = json_encode($event, JSON_THROW_ON_ERROR);
        $message = $context->createMessage($body, [
            'event' => get_class($event)
        ]);

        $context->createProducer()->send($queue, $message);
    }

}
