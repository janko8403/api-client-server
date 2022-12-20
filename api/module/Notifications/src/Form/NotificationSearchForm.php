<?php

namespace Notifications\Form;

use Notifications\Entity\Notification;
use Hr\Form\SearchForm;

class NotificationSearchForm extends SearchForm
{
    public function __construct()
    {
        parent::__construct('notificationSearch');

        $this->add([
            'type' => 'hidden',
            'name' => 'perPage',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'subject',
            'options' => [
                'label' => 'Temat',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Typ',
                'value_options' => Notification::TYPES,
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