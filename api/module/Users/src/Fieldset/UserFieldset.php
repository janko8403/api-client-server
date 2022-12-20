<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.10.17
 * Time: 13:59
 */

namespace Users\Fieldset;

use Configuration\Entity\Position;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Entity\Dictionary;
use Hr\Fieldset\BaseFieldset;
use Laminas\Filter;
use Laminas\I18n\Validator\PhoneNumber;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Regex;
use Users\Entity\Department;
use Users\Entity\User;

class UserFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager, string $name)
    {
        parent::__construct($name);

        $this->setHydrator(new DoctrineHydrator($objectManager, User::class))->setObject(new User());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'checkbox',
            'name' => 'isactive',
            'options' => [
                'label' => 'Czy aktywny',
            ],
        ]);

        $this->add([
            'type' => 'checkbox',
            'name' => 'tempPassword',
            'options' => [
                'label' => 'Hasło tymczasowe',
            ],
        ]);

        $this->add([
            'type' => 'checkbox',
            'name' => 'istester',
            'options' => [
                'label' => 'Tester',
            ],
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Hasło',
            ],
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password2',
            'options' => [
                'label' => 'Potwierdź hasło',
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'configurationPosition',
            'attributes' => [
                'id' => 'configurationPosition',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Pozycja',
                'empty_option' => 'wybierz...',
                'object_manager' => $objectManager,
                'target_class' => Position::class,
                'property' => 'name',
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
            'type' => ObjectSelect::class,
            'name' => 'supervisor',
            'attributes' => [
                'id' => 'supervisior',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Przełożony',
                'empty_option' => 'wybierz...',
                'object_manager' => $objectManager,
                'target_class' => User::class,
                'is_method' => true,
                'property' => 'FullName',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isactive' => true, 'configurationPosition' => [8, 9, 10, 11, 12, 13]],
                        'orderBy' => ['surname' => 'ASC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Imię',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'surname',
            'options' => [
                'label' => 'Nazwisko',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'login',
            'options' => [
                'label' => 'Login',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'phonenumber',
            'options' => [
                'label' => 'Numer telefonu',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add($this->getDictionaryFieldConfig($objectManager, 'position', 'Stanowisko', Dictionary::DIC_POSITIONS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'region', 'Region', Dictionary::DIC_REGIONS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'chain', 'Sieć', Dictionary::DIC_CHAINS, false));

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'departments',
            'attributes' => [
                'multiple' => true,
                'id' => 'departments',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Działy',
                'object_manager' => $objectManager,
                'target_class' => Department::class,
                'property' => 'name',
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
            'name' => 'company',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'referenceNumber',
            'options' => [
                'label' => 'ID użytkownika SAP',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'npsValue',
            'options' => [
                'label' => 'Wartość NPS',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'dailyProductivity',
            'options' => [
                'label' => 'Dzienna produktywność',
            ],
        ]);

        // validation / filter

        $defaultFilter = [
            ['name' => Filter\StringTrim::class],
            ['name' => Filter\StripTags::class],
        ];
        $optional = [
            'required' => false,
            'filters' => $defaultFilter,
        ];
        $nullFilter = [
            [
                'name' => Filter\ToNull::class,
            ],
        ];
        $email = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new EmailAddress(),
            ],
        ];
        $phoneNumber = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new PhoneNumber([
                    'allowedTypes' => [
                        'general',
                        'personal',
                        'mobile',
                    ],
                    'country' => 'PL',
                ]),
            ],
        ];

        $this->inputFilter = [
            'departments' => ['required' => false],
            'name' => $optional,
            'surname' => $optional,
            'chain' => $optional,
            'isActive' => ['required' => false],
            'tempPassword' => ['required' => false],
            'istester' => ['required' => false],
            'configurationPosition' => ['required' => true],
            'position' => ['required' => false],
            'supervisior' => ['required' => false],
            'region' => ['required' => false],
            'login' => [
                'required' => true,
                'filters' => $defaultFilter,
                'validators' => [
                    new \Laminas\Validator\StringLength(['min' => 3]),
                    new \DoctrineModule\Validator\UniqueObject([
                        'object_manager' => $objectManager,
                        // object repository to lookup
                        'object_repository' => $objectManager->getRepository(User::class),

                        // fields to match
                        'fields' => ['login'],
                        'use_context' => true,
                        'message' => 'Podany login już istnieje.',
                    ]),
                ],
            ],
            'email' => $email,
            'phonenumber' => $phoneNumber,
            'password' => [
                'required' => true,
                'filters' => $defaultFilter,
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password2',
                        ],
                    ],
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/',
                        'message' => 'Hasło musi zawierać małe, wielkie litery oraz cyfrę.',
                    ]),
                ],
            ],
            'company' => $optional,
            'referenceNumber' => [
                'required' => true,
                'filters' => $defaultFilter,
            ],
            'npsValue' => $optional,
            'dailyProductivity' => $optional,
        ];
    }
}