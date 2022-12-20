<?php

namespace Customers\Fieldset;

use Customers\Entity\Category;
use Customers\Entity\Customer;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Entity\Dictionary;
use Hr\Entity\Subchain;
use Hr\Fieldset\BaseFieldset;
use Laminas\Filter;
use Laminas\I18n\Validator\PhoneNumber;
use Laminas\Validator\Callback;
use Laminas\Validator\Date;
use Laminas\Validator\EmailAddress;

class CustomerFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager, $regions)
    {
        parent::__construct('customer');
        $this->setHydrator(new DoctrineHydrator($objectManager, Customer::class))->setObject(new Customer());
        $this->setRegions($regions);

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'isActive',
            'options' => [
                'label' => 'Czy aktywny',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'id' => 'email',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'phoneNumber',
            'options' => [
                'label' => 'Telefon',
            ],
            'attributes' => [
                'id' => 'phoneNumber',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'innerCustomerId',
            'options' => [
                'label' => 'Id SAP',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'additionalInformation',
            'options' => [
                'label' => 'Informacje dodatkowe',
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'subchain',
            'attributes' => [
                'id' => 'subchains',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Podsieć',
                'empty_option' => 'wybierz ...',
                'object_manager' => $objectManager,
                'target_class' => Subchain::class,
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
            'type' => 'select',
            'name' => 'payer',
            'attributes' => [
                'empty_option' => 'Wybierz płatnika',
                'id' => 'payer',
            ],
            'options' => [
                'label' => 'Płatnik',
                'empty_option' => 'Wybierz ...',
                'disable_inarray_validator' => true,
            ],
        ]);

        $this->get('payer')->setOption(
            'add-on-append',
            [
                'element' => [
                    'type' => 'button',
                    'name' => 'add',
                    'attributes' => [
                        'class' => 'btn-sl btn-add add-payer-value',
                        'id' => 'payer',
                    ],
                    'options' => [
                        'label' => '<span class="fa fa-plus"></span>',
                        'label_options' => [
                            'disable_html_escape' => true,
                        ],
                    ],
                ],
            ]
        );

        $this->add($this->getDictionaryFieldConfig($objectManager, 'saleStage', 'Etap sprzedaży', Dictionary::DIC_SALE_STAGES, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'format', 'Format', Dictionary::DIC_FORMATS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'priority', 'Priorytet', Dictionary::DIC_CUSTOMER_PRIORITIES, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'region', 'Region', Dictionary::DIC_REGIONS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'size', 'Rozmiar', Dictionary::DIC_SIZES, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'customerGroups', 'Grupy klienta', Dictionary::DIC_CUSTOMER_GROUPS, true));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'chain', 'Sieć', Dictionary::DIC_CHAINS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'visitsFrequency', 'Czestotliwość wizyt', Dictionary::DIC_VISITS_FREQUENCY, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'logisticRegion', 'Region logistyczny', Dictionary::DIC_REGIONS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'customerStatus', 'Status klienta', Dictionary::DIC_CUSTOMER_STATUS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'subformat', 'Podformat', Dictionary::DIC_SUBFORMATS, false));
        $this->add($this->getDictionaryFieldConfig($objectManager, 'subsize', 'Podrozmiar', Dictionary::DIC_SUBSIZES, false));

        $this->addExtendDictionaryButton('saleStage', Dictionary::DIC_SALE_STAGES);
        $this->addExtendDictionaryButton('priority', Dictionary::DIC_CUSTOMER_PRIORITIES);
        $this->addExtendDictionaryButton('format', Dictionary::DIC_FORMATS);
        $this->addExtendDictionaryButton('region', Dictionary::DIC_REGIONS);
        $this->addExtendDictionaryButton('size', Dictionary::DIC_SIZES);
        $this->addExtendDictionaryButton('customerGroups', Dictionary::DIC_CUSTOMER_GROUPS);
        $this->addExtendDictionaryButton('chain', Dictionary::DIC_CHAINS);
        $this->addExtendDictionaryButton('visitsFrequency', Dictionary::DIC_VISITS_FREQUENCY);
        $this->addExtendDictionaryButton('logisticRegion', Dictionary::DIC_REGIONS);
        $this->addExtendDictionaryButton('customerStatus', Dictionary::DIC_CUSTOMER_STATUS);
        $this->addExtendDictionaryButton('subformat', Dictionary::DIC_SUBFORMATS);
        $this->addExtendDictionaryButton('subsize', Dictionary::DIC_SUBSIZES);

        $this->add([
            'type' => 'date',
            'name' => 'maxContactDate',
            'options' => [
                'label' => 'Data planowanej wizyty/kontaktu',
            ],
            'attributes' => [
                'data-provide' => 'datepicker',
            ],
        ]);

        $this->add([
            'type' => 'date',
            'name' => 'saleStageDateChange',
            'options' => [
                'label' => 'Data zmiany etapu sprzedaży',
            ],
            'attributes' => [
                'data-provide' => 'datepicker',
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'category',
            'options' => [
                'label' => 'Kategoria',
                'empty_option' => 'wybierz',
                'object_manager' => $objectManager,
                'target_class' => Category::class,
                'property' => 'name',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ]);

        $optional = ['required' => false];
        $defaultFilter = [
            ['name' => Filter\StringTrim::class],
            ['name' => Filter\StripTags::class],
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
                        'fixed',
                        'personal',
                        'mobile',
                    ],
                    'country' => 'PL',
                ]),
            ],
        ];
        $innerCustomerId = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new Callback([
                    'callback' => function ($value) use ($objectManager) {
                        if (empty($value)) {
                            // empty value allowed
                            return true;
                        }

                        $customerId = $this->getObject()->getId();
                        $customers = $objectManager->getRepository(Customer::class)
                            ->findBy(['innerCustomerId' => $value]);

                        if (empty($customerId)) {
                            // add customer
                            return count($customers) == 0;
                        } else {
                            // edit customer
                            foreach ($customers as $customer) {
                                if ($customer->getId() != $customerId) {
                                    return false;
                                }
                            }

                            return true;
                        }
                    },
                    'message' => 'Podana wartość już istnieje w systemie',
                ]),
            ],
        ];

        $dateOptional = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new Date(),
            ],
        ];

        $this->inputFilter = [
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'innerCustomerId' => $innerCustomerId,
            'logisticRegion' => $optional,
            'saleStage' => $optional,
            'format' => $optional,
            'priority' => $optional,
            'region' => $optional,
            'size' => $optional,
            'customerGroups' => $optional,
            'chain' => $optional,
            'subchain' => $optional,
            'customerStatus' => $optional,
            'subformat' => $optional,
            'subsize' => $optional,
            'payer' => $optional,
            'maxContactDate' => $dateOptional,
            'saleStageDateChange' => $dateOptional,
            'category' => $optional,
            'visitsFrequency' => $optional,
        ];
    }
}
