<?php

namespace Configuration\Fieldset;

use Configuration\Entity\Resource;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Monitorings\Entity\MonitoringCategory;

/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.06.17
 * Time: 16:03
 */
class ResourceFieldset extends Fieldset implements InputFilterProviderInterface
{
    private ObjectManager $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('configuration');

        $this->objectManager = $objectManager;
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new Resource());

        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'id',
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'label',
            'options' => [
                'label' => 'Label',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'route',
            'options' => [
                'label' => 'Ścieżka',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'routeParams',
            'options' => [
                'label' => 'Parametry',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'icon',
            'options' => [
                'label' => 'Ikona',
            ],
        ]);

        $this->add([
            'type' => Element\Number::class,
            'name' => 'sequence',
            'options' => [
                'label' => 'Kolejność',
                'pattern' => '^\d+$',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isUserMenu',
            'options' => [
                'label' => 'Czy menu główne?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isCustomerMenu',
            'options' => [
                'label' => 'Czy menu klienta?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isCustomerMenu',
            'options' => [
                'label' => 'Czy menu klienta?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isUserDetailsMenu',
            'options' => [
                'label' => 'Czy menu użytkownika?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isShortcutMenu',
            'options' => [
                'label' => 'Czy na stronie startowej',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isHidden',
            'options' => [
                'label' => 'Czy ukryte?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'settingSmall',
            'options' => [
                'label' => 'Czy widoczne na telefonie?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'settingMedium',
            'options' => [
                'label' => 'Czy widoczne na tablecie?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'settingLarge',
            'options' => [
                'label' => 'Czy widoczne na PC?',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'parent',
            'attributes' => [
                'multiple' => false,
                'id' => 'parent',
            ],
            'options' => [
                'label' => 'Rodzic',
                'empty_option' => 'Wybierz menu nadrzędne',
                'object_manager' => $objectManager,
                'target_class' => Resource::class,
                'property' => 'name',
                'is_method' => true,
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'cookie',
            'options' => [
                'label' => 'data-cookie',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => [
                'required' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 256,
                        ],
                    ],
                ],
            ],

            'label' => [
                'required' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 255,
                        ],
                    ],
                ],
            ],

            'routeParams' => [
                'required' => false,
                'filters' => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 255,
                        ],
                    ],
                ],
            ],

            'icon' => [
                'required' => false,
                'filters' => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 255,
                        ],
                    ],
                ],
            ],

            'sequence' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Validator\Digits::class,
                    ],
                ],
            ],

            'parent' => [
                'required' => false,

            ],

            'cookie' => [
                'required' => false,
                'validators' => [
                    new \DoctrineModule\Validator\UniqueObject([
                        'object_manager' => $this->objectManager,
                        // object repository to lookup
                        'object_repository' => $this->objectManager->getRepository(Resource::class),

                        // fields to match
                        'fields' => ['cookie'],
                        'use_context' => true,
                    ]),
                ],
            ],

            'monitoringCategory' => [
                'required' => false,

            ],
        ];
    }
}
