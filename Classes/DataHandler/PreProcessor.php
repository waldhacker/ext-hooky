<?php

declare(strict_types = 1);

namespace Waldhacker\Hooksie\DataHandler;

class PreProcessor
{
    public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, $parentObject): void
    {
        if ($table === 'tx_hooksie_hook' && isset($incomingFieldArray['events'])) {
            $events = $incomingFieldArray['events'];
            $incomingFieldArray['events'] = is_array($events) ? json_encode($events, JSON_THROW_ON_ERROR) : $events;
        }
    }
}