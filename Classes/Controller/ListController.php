<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ListController
{


    public function __construct(
        private ModuleTemplateFactory $moduleTemplateFactory,
        private StandaloneView $view,
    )
    {

    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($request);
        $this->view->setTemplateRootPaths(['EXT:hooky/Resources/Private/Templates/']);
        $this->view->setPartialRootPaths(['EXT:hooky/Resources/Private/Partials/']);
        $this->view->setLayoutRootPaths(['EXT:hooky/Resources/Private/Layouts/']);
        $this->view->setTemplate('List');
        $moduleTemplate->setContent($this->view->render());
        return new HtmlResponse($moduleTemplate->renderContent());
    }
}
