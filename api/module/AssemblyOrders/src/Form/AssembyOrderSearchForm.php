<?php

namespace AssemblyOrders\Form;

use Customers\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Form\SearchForm;
use Users\Entity\User;

class AssembyOrderSearchForm extends SearchForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('assemblyOrderSearch');

        $this->add([
            'type' => 'hidden',
            'name' => 'perPage',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'assembly',
            'options' => [
                'label' => 'Dane montażu',
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'user',
            'attributes' => [
                'id' => 'user',
            ],
            'options' => [
                'label' => 'Montażysta',
                'object_manager' => $objectManager,
                'target_class' => User::class,
                'empty_option' => '-',
                'label_generator' => fn(User $targetEntity) => $targetEntity->getFullName(),
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'customer',
            'attributes' => [
                'id' => 'customer',
            ],
            'options' => [
                'label' => 'Sklep',
                'object_manager' => $objectManager,
                'target_class' => Customer::class,
                'empty_option' => '-',
                'label_generator' => fn(Customer $targetEntity) => $targetEntity->getActiveData()->getName(),
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isActive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'measurementStatus',
            'options' => [
                'empty_option' => '-',
                'label' => 'Status pomiaru',
            ],
            'attributes' => [
                'style' => 'width: 100%',
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'installationStatus',
            'options' => [
                'empty_option' => '-',
                'label' => 'Status montażu',
            ],
            'attributes' => [
                'style' => 'width: 100%',
            ],
        ]);
        $this->add([
            'type' => 'date',
            'name' => 'dateFrom',
            'options' => [
                'label' => 'Data od',
            ],
        ]);
        $this->add([
            'type' => 'date',
            'name' => 'dateTo',
            'options' => [
                'label' => 'Data do',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'orderId',
            'options' => [
                'label' => 'ID zlecenia',
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'accepted',
            'options' => [
                'label' => 'Przyjęte',
                'empty_option' => '-',
                'value_options' => [
                    '1' => 'Tak',
                    '2' => 'Nie',
                ],
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