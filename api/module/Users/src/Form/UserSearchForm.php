<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.10.17
 * Time: 10:09
 */

namespace Users\Form;


use Configuration\Entity\Position;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Entity\DictionaryDetails;
use Hr\Form\SearchForm;
use Users\Entity\User;

class UserSearchForm extends SearchForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('userSearch');

        $this->add([
            'type' => 'hidden',
            'name' => 'perPage',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Imię i nazwisko',
            ],
            'attributes' => [
                'style' => 'width: 100px',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'style' => 'width: 100px',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'phone',
            'options' => [
                'label' => 'Telefon',
            ],
            'attributes' => [
                'style' => 'width: 100px',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'login',
            'options' => [
                'label' => 'Login',
            ],
            'attributes' => [
                'style' => 'width: 100px',
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'position',
            'attributes' => [
                'id' => 'position',
                'multiple' => true,
            ],
            'options' => [
                'label' => 'Pozycja',
                'object_manager' => $objectManager,
                'target_class' => Position::class,
                'property' => 'name',
                'is_method' => true,
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isActive' => true],
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'city',
            'options' => [
                'label' => 'Miejscowość',
            ],
            'attributes' => [
                'style' => 'width: 100px',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'isactive',
            'options' => [
                'label' => 'Czy Aktywny',
                'value_options' => [
                    '1' => 'Aktywni',
                    '0' => 'Nie aktywni',
                    '2' => 'Wszyscy',
                ],
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'region',
            'attributes' => [
                'multiple' => true,
                'id' => 'region',
            ],
            'options' => [
                'label' => 'Region',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_REGIONS, 'isactive' => 1],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'supervisor',
            'attributes' => [
                'multiple' => true,
                'id' => 'supervisor',
            ],
            'options' => [
                'label' => 'Przełożony',
                'object_manager' => $objectManager,
                'target_class' => User::class,
                'label_generator' => function (User $targetEntity) {
                    return $targetEntity->getFullName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isactive' => 1],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'customer',
            'options' => [
                'label' => 'Klient',
            ],
            'attributes' => [
                'id' => 'customer',
                'style' => 'width: 100%',
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