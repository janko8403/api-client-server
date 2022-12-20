<?php

namespace Configuration\Form;
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 27.06.17
 * Time: 16:41
 */

use Hr\Form\SearchForm;

class MenuSearchFrom extends SearchForm
{
    public function __construct()
    {
        parent::__construct("menuSearch");

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
            'type' => 'text',
            'name' => 'route',
            'options' => [
                'label' => 'Ścieżka',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'label',
            'options' => [
                'label' => 'Label',
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

