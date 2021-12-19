<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'site',
    'hooksie',
    'bottom',
    null,
    [
        //'navigationComponentId' => 'TYPO3/CMS/Backend/Tree/FileStorageTreeContainer',
        'routeTarget' => \Waldhacker\Hooksie\Controller\ListController::class,
        'access' => 'admin',
        'name' => 'site_hooksie',
        'iconIdentifier' => 'hooksie',
        'labels' => 'LLL:EXT:hooksie/Resources/Private/Language/hooksie.xlf'
    ]
);
