<?php

namespace Hr\View\Helper;

use Hr\Form\Element\Dropdown as DropdownElement;
use Laminas\View\Helper\AbstractHelper;

class Dropdown extends AbstractHelper
{
    private static string $staticFormat = <<<HTML
        <div class="dropdown" id="%s">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                %s <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">%s</ul>
        </div>
HTML;

    public function __invoke(DropdownElement $element)
    {
        $html = '';
        $tpl = "<li><a href='%s' class='dropdown-item'>%s</a></li>";
        $urlHelper = $this->getView()->getHelperPluginManager()->get('url');
        $urlParamHelper = $this->getView()->getHelperPluginManager()->get('urlParam');

        $valueOptions = $element->getOption('value_options');
        foreach ($valueOptions as $name => $label) {
            $html .= sprintf(
                $tpl,
                $urlHelper($element->getOption('route'), ['type' => $name, 'template-id' => $urlParamHelper('id')]),
                $label
            );
        }

        return sprintf(
            self::$staticFormat,
            $element->getAttribute('id'),
            $element->getValue(),
            $html
        );
    }
}