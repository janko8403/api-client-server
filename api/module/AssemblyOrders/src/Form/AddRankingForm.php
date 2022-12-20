<?php

namespace AssemblyOrders\Form;

use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Laminas\Form\Form;
use Users\Entity\User;

class AddRankingForm extends Form
{
    public function __construct(private ObjectManager $objectManager)
    {
        parent::__construct('addRankingForm');

        $this->setOptions([
            'layout' => \TwbsHelper\Form\View\Helper\Form::LAYOUT_INLINE,
            'gutter' => 2,
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'user',
            'options' => [
                'empty_option' => 'wybierz',
                'object_manager' => $this->objectManager,
                'target_class' => User::class,
                'property' => 'fullNameWithSAP',
                'is_method' => true,
                'find_method' => [
                    'name' => 'fetchAllActive',
                ],
            ],
            'attributes' => [
                'id' => 'user',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-primary',
                'value' => 'Dodaj',
            ],
        ]);
    }
}