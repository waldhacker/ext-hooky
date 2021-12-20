<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Waldhacker\Hooky\Repository\HookConfigurationRepository;

class ListController
{

    protected LanguageService $languageService;

    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected StandaloneView $view,
        protected ResponseFactoryInterface $responseFactory,
        protected HookConfigurationRepository $configurationRepository,
        protected IconFactory $iconFactory,
        protected LanguageServiceFactory $languageServiceFactory,
        protected UriBuilder $uriBuilder,
    )
    {
        $this->languageService = $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER']);
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->setupView();

        $records = $this->configurationRepository->fetchAll();
        $this->view->assign('records', $records);

        $fluidContent = $this->view->render();
        $moduleTemplate = $this->moduleTemplateFactory->create($request);
        $this->getButtons($moduleTemplate, $request);
        $moduleTemplate->setContent($fluidContent);

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/html; charset=utf-8');
        $response->getBody()->write($moduleTemplate->renderContent());

        return $response;
    }

    /**
     * @return void
     */
    private function setupView(): void
    {
        $this->view->setTemplateRootPaths(['EXT:hooky/Resources/Private/Templates/']);
        $this->view->setPartialRootPaths(['EXT:hooky/Resources/Private/Partials/']);
        $this->view->setLayoutRootPaths(['EXT:hooky/Resources/Private/Layouts/']);
        $this->view->setTemplate('List');
    }


    /**
     * Create document header buttons
     */
    protected function getButtons(ModuleTemplate $moduleTemplate, ServerRequestInterface $request): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // Create new
        $newRecordButton = $buttonBar->makeLinkButton()
            ->setHref(
                (string)$this->uriBuilder->buildUriFromRoute(
                    'record_edit',
                    [
                        'edit' => [
                            'tx_hooky_hook' => ['new'],
                        ],
                        'returnUrl' => (string)$this->uriBuilder->buildUriFromRoute('site_hooky'),
                    ]
                )
            )
            ->setTitle('Add')
            ->setIcon($this->iconFactory->getIcon('actions-add', Icon::SIZE_SMALL));
        $buttonBar->addButton($newRecordButton, ButtonBar::BUTTON_POSITION_LEFT, 10);

        // Reload
        $reloadButton = $buttonBar->makeLinkButton()
            ->setHref($request->getAttribute('normalizedParams')->getRequestUri())
            ->setTitle($this->languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($reloadButton, ButtonBar::BUTTON_POSITION_RIGHT);

        // Shortcut
        $shortcutButton = $buttonBar->makeShortcutButton()
            ->setRouteIdentifier('site_hooky')
            ->setDisplayName($this->languageService->sL('LLL:EXT:hooky/Resources/Private/Language/hooky.xlf:mlang_labels_tablabel'));
        $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }
}
