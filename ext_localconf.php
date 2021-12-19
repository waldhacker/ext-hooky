<?php

use Waldhacker\Hooksie\DataHandler\PostProcessor;
use Waldhacker\Hooksie\DataHandler\PreProcessor;
use Waldhacker\Hooksie\FormEngine\HookEventsRenderType;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1639321484] = [
    'nodeName' => 'hookEvents',
    'priority' => '70',
    'class' => HookEventsRenderType::class
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = PreProcessor::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = PostProcessor::class;
