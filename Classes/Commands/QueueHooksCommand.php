<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\Commands;

use Doctrine\DBAL\DriverManager;
use Enqueue\Dbal\DbalContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Waldhacker\Hooky\Configuration\HookConfigurationService;

class QueueHooksCommand extends Command
{

    public function __construct(
        protected ConnectionPool $connectionPool,
        protected HookConfigurationService $hookConfigurationService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // use native doctrine dbal connection instead of TYPO3s overwritten one
        // as the overwritten querybuilder is not fully compatible with
        // enqueues queries
        $connection = DriverManager::getConnection($GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']);
        $context = new DbalContext($connection);
        $hookQueue = $context->createQueue('events');
        $consumer = $context->createConsumer($hookQueue);
        $hooksByEvents = $this->hookConfigurationService->getConfiguredHooksByEvents();

        while ($message = $consumer->receive(1)) {
            $event = $message->getProperty('event');
            if ($event && count($hooksByEvents[$event]) > 0) {
                foreach ($hooksByEvents[$event] as $hookConfig) {
                    $hookQueue = $context->createQueue('hooks');
                    $body = $message->getBody();
                    $hookMessage = $context->createMessage($body, [
                        'url' => $hookConfig->getUrl(),
                        'secret' => $hookConfig->getSecret()
                    ]);
                    $context->createProducer()->send($hookQueue, $hookMessage);
                }
            }
            $consumer->acknowledge($message);
        }

        return 0;
    }
}
