<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:hooky/Resources/Private/Language/locallang_db.xlf:tx_hooky_hook',
        'label' => 'url',
        'crdate' => 'createdon',
        'cruser_id' => 'createdby',
        'tstamp' => 'updatedon',
        'versioningWS' => false,
        'groupName' => 'system',
        'default_sortby' => 'url',
        'rootLevel' => 1,
        'security' => [
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
        ],
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'disabled',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'typeicon_classes' => [
            'default' => 'hooky',
        ],
        'searchFields' => 'url',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,url,secret,events,,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, --palette--;;visibility',
        ],
    ],
    'palettes' => [
        'visibility' => [
            'showitem' => 'disabled, --linebreak--, starttime, endtime',
        ],
    ],
    'columns' => [
        'disabled' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ],
        'url' => [
            'label' => 'LLL:EXT:hooky/Resources/Private/Language/locallang_db.xlf:tx_hooky_hook.url',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'folder,file,mail,page,spec,telephone',
                            'blindLinkFields' => 'class,target,title',
                        ],
                    ],
                ],
                'max' => 2048,
            ],
        ],
        'secret' => [
            'label' => 'LLL:EXT:hooky/Resources/Private/Language/locallang_db.xlf:tx_hooky_hook.secret',
            'config' => [
                'type' => 'input',
                'max' => 2048,
            ],
        ],
        'events' => [
            'label' => 'LLL:EXT:hooky/Resources/Private/Language/locallang_db.xlf:tx_hooky_hook.events',
            'config' => [
                'type' => 'user',
                'renderType' => 'hookEvents'
            ],
        ],
    ],
];
