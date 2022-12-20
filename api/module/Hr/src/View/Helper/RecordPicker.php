<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Hr\Form\Element\RecordPicker as RecordPickerElement;

class RecordPicker extends AbstractHelper
{

    private static $staticFormat = <<<HTML
        <p class="form-control-static">
            <a href="%s" class="btn btn-record-picker full-width chose-btn" id="%s">
                Wybrano: 
                <span class="count">%s</span>
            </a>
        </p>
HTML;

    public function __invoke(RecordPickerElement $element)
    {
        $route = $element->getOption('route');
        $params = $element->getOption('route-params') ?? [];
        $urlHelper = $this->getView()->getHelperPluginManager()->get('url');

        return sprintf(
            self::$staticFormat,
            $urlHelper($route, $params),
            $element->getAttribute('id'),
            (int)$element->getOption('count')
        );
    }
}