<?php

namespace Settings\Fieldset;

use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Filter;

class DictionaryDetailFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('dictionaryDetail');

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ]
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'key',
            'options' => [
                'label' => 'Klucz',
            ]
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'isActive',
            'options' => [
                'label' => 'Aktywny',
            ]
        ]);
	}

    public function getInputFilterSpecification()
    {
        $std = [
            'required' => true,
            'validators' => [
            ],
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ];

        return [
            'name' => $std,
            'key' => [
                'required' => false,
                'filters' => [
                    new Filter\ToNull(
                        Filter\ToNull::TYPE_ALL
                    ),

                ],
            ],
            'isActive' => $std,
        ];
    }
}
