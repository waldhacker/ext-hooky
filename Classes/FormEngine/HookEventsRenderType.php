<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\FormEngine;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Waldhacker\Hooksie\Configuration\HookableEvents;
use Waldhacker\Hooksie\Events\RecordCreatedEvent;
use Waldhacker\Hooksie\Events\RecordUpdatedEvent;

class HookEventsRenderType extends AbstractFormElement
{

    public function render(): array
    {
        $view = $this->setupView();

        $parameterArray = $this->data['parameterArray'];
        $resultArray = $this->initializeResultArray();
        $itemValue = $parameterArray['itemFormElValue'];
        $itemName = $parameterArray['itemFormElName'];

        $fieldInformationResult = $this->renderFieldInformation();
        $fieldInformationHtml = $fieldInformationResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldInformationResult, false);

       $hookableEvents = GeneralUtility::makeInstance(HookableEvents::class)->get();
        $view->assignMultiple(
            [
                'fieldInformation' => $fieldInformationHtml,
                'fieldName' => $itemName,
                'events' => $hookableEvents,
                'fieldValue' => $itemValue !== '' ? json_decode($itemValue, true, 512, JSON_THROW_ON_ERROR) : ''
            ]
        );

        $resultArray['html'] = $view->render();
        return $resultArray;
    }

    private function setupView(): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths(['EXT:hooksie/Resources/Private/Templates/FormEngine/']);
        $view->setPartialRootPaths(['EXT:hooksie/Resources/Private/Partials/FormEngine/']);
        $view->setLayoutRootPaths(['EXT:hooksie/Resources/Private/Layouts/FormEngine/']);
        $view->setTemplate('HookEvents');
        return $view;
    }
}
