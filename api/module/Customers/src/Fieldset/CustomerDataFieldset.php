<?php

namespace Customers\Fieldset;

use Customers\Entity\CustomerData;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Proxy\__CG__\Hr\Entity\DictionaryDetails;
use Hr\Entity\Dictionary;
use Hr\Fieldset\BaseFieldset;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Validator\Regex;

class CustomerDataFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('customerData');
        $this->setHydrator(new DoctrineHydrator($objectManager, CustomerData::class))->setObject(new CustomerData());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'nip',
            'options' => [
                'label' => 'NIP',
            ],
            'attributes' => [
                'id' => 'nip',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'regon',
            'options' => [
                'label' => 'REGON',
            ],
            'attributes' => [
                'id' => 'regon',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
            'attributes' => [
                'id' => 'name',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'name2',
            'options' => [
                'label' => 'Nazwa 2',
            ],
            'attributes' => [
                'id' => 'name2',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'streetName',
            'options' => [
                'label' => 'Ulica',
            ],
            'attributes' => [
                'id' => 'streetName',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'streetNumber',
            'options' => [
                'label' => 'Numer ulicy',
            ],
            'attributes' => [
                'id' => 'streetNumber',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'localNumber',
            'options' => [
                'label' => 'Number lokalu',
            ],
            'attributes' => [
                'id' => 'localNumber',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'zipCode',
            'options' => [
                'label' => 'Kod pocztowy',
            ],
            'attributes' => [
                'id' => 'zipCode',
                'data-mask' => '00-000',
                'placeholder' => '00-000',
                'class' => 'mask',
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'city',
            'attributes' => [
                'empty_option' => 'Wybierz miasto',
                'id' => 'city',
            ],
            'options' => [
                'label' => 'Miasto',
                'disable_inarray_validator' => true,
            ],
        ]);

        // $this->add($this->getDictionaryFieldConfig($objectManager, 'streetPrefix', 'Prefix ulicy', Dictionary::DIC_STREET_PREFIXES, false));

        $prefixOptions = $objectManager->getRepository(DictionaryDetails::class)->getStreetPrefix();

        $this->add([
            'type' => 'select',
            'name' => 'streetPrefix',
            'attributes' => [
                'multiple' => false,
                'empty_option' => 'Wybierz prefix',
                'id' => 'streetPrefix',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Prefix ulicy',
                'empty_option' => 'Wybierz',
                'value_options' => $prefixOptions,

            ],

        ]);


        $optional = ['required' => false];
        $defaultFilter = [
            ['name' => StringTrim::class],
            ['name' => StripTags::class],
        ];

        $this->inputFilter = [
            'nip' => [
                'required' => false,
                'filter' => $defaultFilter,
            ],
            'regon' => [
                'required' => false,
                'filter' => $defaultFilter,
            ],
            'name' => [
                'required' => true,
                'filter' => $defaultFilter,
            ],
            'streetName' => [
                'required' => false,
                'filter' => $defaultFilter,
            ],
            'streetNumber' => [
                'required' => false,
                'filter' => $defaultFilter,
            ],
            'localNumber' => [
                'required' => false,
                'filter' => $defaultFilter,
            ],
            'zipCode' => [
                'required' => false,
                'validators' => [
                    new Regex([
                        'pattern' => '/^\d{2}-\d{3}$/',
                        'message' => 'Niepoprawny format kodu pocztowego',
                    ]),
                ],
                'filter' => $defaultFilter,
            ],
            'city' => [
                'required' => true,
            ],
            'streetPrefix' => $optional,
        ];
    }
}
