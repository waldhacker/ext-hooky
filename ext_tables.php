<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'site',
    'hooky',
    'bottom',
    null,
    [
        //'navigationComponentId' => 'TYPO3/CMS/Backend/Tree/FileStorageTreeContainer',
        'routeTarget' => \Waldhacker\Hooky\Controller\ListController::class,
        'access' => 'admin',
        'name' => 'site_hooky',
        'iconIdentifier' => 'hooky',
        'labels' => 'LLL:EXT:hooky/Resources/Private/Language/hooky.xlf'
    ]
);
