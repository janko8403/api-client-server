<?php

namespace Customers\Form;

use Customers\Fieldset\CustomerDataFieldset;
use Customers\Fieldset\CustomerFieldset;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\RecordForm;
use Laminas\Form\Element\Select;

class CustomerTemplateForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('customerTemplateForm');

        $this->objectManager = $objectManager;

        $this->setAttribute('method', 'post');

        $customerFieldset = new CustomerFieldset($objectManager, 'customer');
        foreach ($customerFieldset as $item) {
            if ($item instanceof Select) {
                if (!$item->getAttribute('multiple')) {
                    $item->setEmptyOption('');
                }
            }
        }
        $this->add($customerFieldset);

        $customerDataFieldset = new CustomerDataFieldset($objectManager, 'customerData');
        foreach ($customerDataFieldset as $item) {
            if ($item instanceof Select) {
                if (!$item->getAttribute('multiple')) {
                    $item->setEmptyOption('');
                }
            }
        }
        $this->add($customerDataFieldset);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz',
            ],
        ]);
    }
}