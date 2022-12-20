<?php

namespace Settings\Form;

use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Form;

class AddressBarForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('addressBar');

        $this->setAttribute('method', 'post');

        $this->add([
            'type' => 'textarea',
            'name' => 'addressBarCustomer',
            'options' => [
                'label' => 'Belka adresowa klienta',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'addressBarUser',
            'options' => [
                'label' => 'Belka adresowa użytkownika',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'addressBarCustomer',
            'options' => [
                'label' => 'Belka adresowa klienta',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'customerRegionSame',
            'options' => [
                'label' => 'Ten sam region klienta',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'customerRegionDifferent',
            'options' => [
                'label' => 'Inny region klienta',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz'
            ],
        ]);
    }
}