<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 15.11.2016
 * Time: 10:58
 */

namespace Hr\View\Helper;

use Hr\Form\SearchForm;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\View\Helper\AbstractHelper;

class SearchFormHelper extends AbstractHelper
{
    function __invoke(SearchForm $form, bool $setDefaultWidths = false, ?int $splitRows = null)
    {
        $mobileDetactHelper = $this->getView()->getHelperPluginManager()->get('mobileDetect')();
        if ($mobileDetactHelper->isMobile()) {
            return $this->renderMobile($form);
        } else {
            return $this->renderDesktop($form, $setDefaultWidths, $splitRows);
        }
    }

    private function renderMobile(SearchForm $form)
    {
        if ($form->count() == 0) {
            return '';
        }

        $html = <<<HTML
        <div style="z-index:999; position:relative">
            <div class="search-form-mobile">
                %s
            </div> 
        </div>
HTML;

        $formHelper = $this->getView()->getHelperPluginManager()->get('form');
        $urlHelper = $this->getView()->getHelperPluginManager()->get('url');
        $queryParamsHelper = $this->getView()->getHelperPluginManager()->get('queryParams');

        $form->setAttribute('class', $form->getAttribute('class') . ' form-horizontal');

        if (empty($form->getAttribute('action'))) {
            $form->setAttribute('action', $urlHelper(null, [], ['query' => $queryParamsHelper()], true));
        }

        foreach ($form as $element) {
            if ($element instanceof \Laminas\Form\Element\Checkbox) {
                $element->setOptions([
                    'column-size' => 'xs-12',
                ]);
            } else {
                $element->setOptions([
                    'column-size' => 'xs-8',
                    'label_attributes' => ['class' => 'col-xs-4'],
                ]);
            }
            $element->setOption('twb-row-class', 'search-form-row-mobile');
        }

        return sprintf(
            $html,
            $formHelper->__invoke($form, 'horizontal')
        );
    }

    private function renderDesktop(SearchForm $form, bool $setDefaultWidths, ?int $splitRows = null)
    {
        $splitRows = $splitRows ?? 100;

        $rowHeaderTpl = <<<HTML
<thead>
    <tr class="search-header">
        <th>%s</th>
    </tr>
</thead>
HTML;
        $rowFieldsTpl = <<<HTML
<tbody>
    <tr>%s</tr>
</tbody>
HTML;


        $html = <<<HTML
        <div style="z-index:999; position:relative">
            <div class="search-form">
                %s
                <table class="searchTable table-condensed">
                    %s
                </table>
                %s%s
            </div> 
        </div>
HTML;

        $labels = [];
        $hiddenElements = [];
        $elements = [];
        $formElementHelper = $this->getView()->getHelperPluginManager()->get('formElement');
        $formCheckboxHelper = $this->getView()->getHelperPluginManager()->get('formCheckbox');
        $formHelper = $this->getView()->getHelperPluginManager()->get('form');
        $urlHelper = $this->getView()->getHelperPluginManager()->get('url');
        $queryParamsHelper = $this->getView()->getHelperPluginManager()->get('queryParams');

        if (empty($form->getAttribute('action'))) {
            $form->setAttribute('action', $urlHelper(null, [], ['query' => $queryParamsHelper()], true));
        }

        foreach ($form as $element) {
            if ($element instanceof Hidden) {
                $hiddenElements[] = $element;
                continue;
            }

            // set default widths
            if ($setDefaultWidths) {
                $width = null;
                if ($element instanceof Select) {
                    $width = 100;
                } elseif ($element instanceof Date) {
                    $width = 100;
//                } elseif ($element instanceof Text) {
//                    $width = 100;
                }

                if ($width) {
                    $element->setAttribute('style', "width: {$width}px;");
                }
            }
            $element->setAttribute('class', $element->getAttribute('class') . ' input-sm');

            $messages = $form->getMessages($element->getName());
            if (!empty($messages)) {
                $element->setOption(
                    'add-on-append',
                    '<span class="glyphicon glyphicon-exclamation-sign" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . implode("<br>", $messages) . '"></span>'
                );
                $elementHtml = "<td class='has-error'>" . $formElementHelper->__invoke($element) . "</td>";
            } else {
                if ($element instanceof \Laminas\Form\Element\Checkbox) {
                    $elementClone = clone $element;
                    $elementClone->setLabel('');
                    $elementHtml = "<td class='text-center'>" . $formCheckboxHelper->__invoke($elementClone) . "</td>";
                } else {
                    $elementHtml = "<td>" . $formElementHelper->__invoke($element) . "</td>";
                }
            }

            $elements[] = $elementHtml;
            $labels[] = $element->getLabel();
        }

        // no form fields, don't display the form
        if (count($elements) == 1) {
            // only search button visible
            return '';
        }

        // render hidden elements (if exist)
        $hiddenHtml = '';
        if ($hiddenElements) {
            foreach ($hiddenElements as $elem) {
                $hiddenHtml .= $formElementHelper->__invoke($elem);
            }
        }

        // empty form (only search button), don't render
        if (count($elements) == 1) {
            return '';
        }

        $parts = '';
        $labelChunks = array_chunk($labels, $splitRows);
        $fieldChunks = array_chunk($elements, $splitRows);
        foreach ($labelChunks as $i => $ch) {
            $parts .= sprintf($rowHeaderTpl, implode('</th><th>', $ch));
            $parts .= sprintf($rowFieldsTpl, implode('', $fieldChunks[$i]));
        }

        return sprintf(
            $html,
            $formHelper->openTag($form, null),
            $parts,
            $hiddenHtml,
            $formHelper->closeTag()
        );
    }

}
