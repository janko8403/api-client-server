<?php

namespace Notifications\Form;

use Doctrine\Persistence\ObjectManager;
use Hr\Form\SearchForm;

class SmsHistorySearchForm extends SearchForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('smsHistorySearch');

        $this->add([
            'type' => 'hidden',
            'name' => 'perPage',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Imię nazwisko',
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