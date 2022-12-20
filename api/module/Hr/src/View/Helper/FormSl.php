<?php

namespace Hr\View\Helper;

use Hr\Form\RecordForm;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Text;
use Laminas\View\Helper\AbstractHelper;

class FormSl extends AbstractHelper
{
    private RecordForm $form;

    public function __invoke(RecordForm $form, string $layout, int $columns = 1): string
    {
        $this->form = $form;
        $this->form->prepare();
        $html = '<div class="row">';
        $fields = [];
        $formHelper = $this->getView()->getHelperPluginManager()->get('form');
        $formRowHelper = $this->getView()->getHelperPluginManager()->get('formRow');
        $formElementHelper = $this->getView()->getHelperPluginManager()->get('formElement');
        $translate = $this->getView()->getHelperPluginManager()->get('translate');

        // iterate through field order
        foreach ($form->getFieldOrder() as $f) {
            $chunks = explode('\\', $f);
            $formField = $this->get($chunks);

            // disable field if not empty - configuration setting
            $id = $formField->getAttribute('id');
            if ($id && $form->disabledIfNotEmpty($id)) {
                if (!empty($formField->getValue())) {
                    if ($formField instanceof Text) {
                        $formField->setAttribute('readonly', true);
                    } else {
                        $formField->setAttribute('disabled', true);
                        $formField->setOption('add-on-append', null);
                    }
                }
            }
            $fields[] = $formRowHelper->__invoke($formField);
        }

        $break = ceil(count($fields) / $columns);
        $fieldChunks = array_chunk($fields, $break);

        foreach ($fieldChunks as $ch) {
            $html .= sprintf(
                "<div class='col-md-%d'>%s</div>",
                ceil(12 / $columns),
                implode('', $ch)
            );
        }

        // iterate through remaining form elements (button)
        $buttons = ['<a href="#" class="btn btn-default go-back">' . $translate('Wyjdź') . '</a>'];
        foreach ($form->getElements() as $element) {
            $buttons[] = $formElementHelper->__invoke($element);
        }
        $html .= sprintf('<div class="col-md-12 d-flex justify-content-end">%s</div>', implode('&nbsp;', $buttons));

        $html .= "</div>";

        return $formHelper->__invoke(null, $layout)->openTag($form)
            . $html
            . $formHelper->__invoke(null, $layout)->closeTag($form);
    }

    private function get(array $chunks)
    {
        $formField = null;

        foreach ($chunks as $c) {
            if (is_null($formField)) {
                $formField = $this->form->get($c);
            } else {
                $formField = $formField->get($c);
            }

            if ($formField instanceof Collection) {
                $formField = $formField->getFieldsets()[0];
            }
        }

        return $formField;
    }
}