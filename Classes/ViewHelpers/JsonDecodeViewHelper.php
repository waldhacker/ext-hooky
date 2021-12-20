<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class JsonDecodeViewHelper extends AbstractViewHelper
{
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return json_decode($renderChildrenClosure(), true, 512, JSON_THROW_ON_ERROR);
    }

}
