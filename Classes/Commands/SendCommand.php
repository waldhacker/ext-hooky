<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\Commands;

use Doctrine\DBAL\DriverManager;
use Enqueue\Dbal\DbalContext;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use Waldhacker\Hooksie\Configuration\HookConfigurationService;
use Waldhacker\Hooksie\DTO\HookConfiguration;

class SendCommand extends Command
{

    public function __construct(
        protected ConnectionPool $connectionPool,
        protected HookConfigurationService $hookConfigurationService,
        protected RequestFactory $requestFactory,
        protected ClientInterface $client,
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
        $hookQueue = $context->createQueue('hooks');
        $consumer = $context->createConsumer($hookQueue);
        while ($message = $consumer->receive(1)) {
            $hookConfiguration = new HookConfiguration($message->getProperty('url'), $message->getProperty('secret'));
            $request = $this->requestFactory->createRequest('GET', $hookConfiguration->getUrl());
            $body = $request->getBody();
            $body->write($message->getBody());
            $request = $request->withBody($body);
            $signature = base64_encode(hash_hmac('sha256', $message->getBody(), $hookConfiguration->getSecret()));

            $request = $request->withHeader('X-TYPO3-HookSignature', $signature);
            $this->client->sendRequest($request);

            $consumer->acknowledge($message);
        }
        return 0;
    }
}
