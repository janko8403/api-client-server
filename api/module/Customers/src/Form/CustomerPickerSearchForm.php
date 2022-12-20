<?php

namespace Customers\Form;

use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Entity\DictionaryDetails;
use Hr\Entity\Subchain;
use Hr\Form\PositionVisibilityAwareInterface;
use Hr\Form\SearchForm;
use Settings\Entity\PositionVisibility;
use Settings\Service\PositionVisibilityService;
use Users\Entity\User;

class CustomerPickerSearchForm extends SearchForm implements PositionVisibilityAwareInterface
{
    public function __construct(
        ObjectManager             $objectManager,
        PositionVisibilityService $positionVisibilityService,
        array                     $identity
    )
    {
        parent::__construct('customerPickerSearch');
        $this->setPositionVisibilityService($positionVisibilityService);
        $this->setIdentity($identity);

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
            'name' => 'innerId',
            'options' => [
                'label' => 'Id wewnetrzne',
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'chain',
            'attributes' => [
                'multiple' => true,
                'id' => 'chain',
            ],
            'options' => [
                'label' => 'Sieć',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_CHAINS, 'isactive' => 1],
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'visitsFrequency',
            'attributes' => [
                'multiple' => true,
                'id' => 'visitsFrequency',
            ],
            'options' => [
                'label' => 'Częstotliwość wizyt',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_VISITS_FREQUENCY, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $criteria = [
            'dictionary' => \Hr\Entity\Dictionary::DIC_REGIONS,
            'isactive' => 1,
        ];
        $regions = $this->getRegionsVisibility(PositionVisibility::FIELD_CUSTOMERS);
        if (!is_null($regions)) {
            $criteria['id'] = $regions;
        }
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
                        'criteria' => $criteria,
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'size',
            'attributes' => [
                'multiple' => true,
                'id' => 'size',
            ],
            'options' => [
                'label' => 'Rozmiar',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_SIZES, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'priority',
            'attributes' => [
                'multiple' => true,
                'id' => 'priority',
            ],
            'options' => [
                'label' => 'Priorytet',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_CUSTOMER_PRIORITIES, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'format',
            'attributes' => [
                'multiple' => true,
                'id' => 'format',
            ],
            'options' => [
                'label' => 'Format',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_FORMATS, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'street',
            'options' => [
                'label' => 'Ulica',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'zipCode',
            'options' => [
                'label' => 'Kod pocztowy',
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'city',
            'attributes' => [
                'id' => 'city',
                'multiple' => false,
            ],
            'options' => [
                'label' => 'Miasto',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'nip',
            'options' => [
                'label' => 'NIP',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'payerName',
            'options' => [
                'label' => 'Nazwa płatnika',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'payerNip',
            'options' => [
                'label' => 'Nip płatnika',
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'logisticRegion',
            'attributes' => [
                'multiple' => true,
                'id' => 'logisticRegion',
            ],
            'options' => [
                'label' => 'Region logistyczny',
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
            'name' => 'subchain',
            'attributes' => [
                'multiple' => true,
                'id' => 'subchain',
            ],
            'options' => [
                'label' => 'podsieć',
                'object_manager' => $objectManager,
                'target_class' => Subchain::class,
                'label_generator' => function (Subchain $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isActive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'creator',
            'attributes' => [
                'multiple' => true,
                'id' => 'creator',
            ],
            'options' => [
                'label' => 'Utworzył',
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
            'type' => ObjectSelect::class,
            'name' => 'subsizes',
            'attributes' => [
                'multiple' => true,
                'id' => 'subsizes',
            ],
            'options' => [
                'label' => 'Podregion',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_SUBSIZES, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'subformats',
            'attributes' => [
                'multiple' => true,
                'id' => 'subformats',
            ],
            'options' => [
                'label' => 'Podformat',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_SUBFORMATS, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'saleStage',
            'attributes' => [
                'multiple' => true,
                'id' => 'saleStage',
            ],
            'options' => [
                'label' => 'Etap sprzedaży',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_SALE_STAGES, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'customerStatus',
            'attributes' => [
                'multiple' => true,
                'id' => 'customerStatus',
            ],
            'options' => [
                'label' => 'Status klienta',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_CUSTOMER_STATUS, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'phoneNumber',
            'options' => [
                'label' => 'Numer Telefonu',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'regon',
            'options' => [
                'label' => 'REGON',
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
            'type' => 'submit',
            'name' => 'search',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Szukaj',
            ],
        ]);
    }
}