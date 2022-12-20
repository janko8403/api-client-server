<?php

namespace DocumentTemplates\Form;

use Doctrine\Persistence\ObjectManager;
use Hr\Form\SearchForm;

class DocumentTemplateSearchForm extends SearchForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('documentTemplateSearch');

        $this->add([
            'type' => 'hidden',
            'name' => 'perPage',
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'isActive',
            'options' => [
                'label' => 'Aktywne',
                'value_options' => [
                    0 => 'nie',
                    1 => 'tak',
                ],
                'empty_option' => 'wybierz',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'search',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Szukaj',
            ],
        ]);
    }
}