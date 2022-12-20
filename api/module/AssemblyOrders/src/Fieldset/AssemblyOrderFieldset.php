<?php

namespace AssemblyOrders\Fieldset;

use AssemblyOrders\Entity\AssemblyOrder;
use Customers\Entity\Customer;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Fieldset\BaseFieldset;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\I18n\Validator\IsFloat;
use Laminas\I18n\Validator\IsInt;
use Laminas\Validator\EmailAddress;

class AssemblyOrderFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('assemblyOrder');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new AssemblyOrder());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'idMeasurementOrder',
            'options' => [
                'label' => 'idMeasurementOrder',
            ],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'idInstallationOrder',
            'options' => [
                'label' => 'idInstallationOrder',
            ],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'idParentInstallationOrder',
            'options' => [
                'label' => 'idParentInstallationOrder',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'measurementStatus',
            'options' => [
                'label' => 'measurementStatus',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'installationStatus',
            'options' => [
                'label' => 'installationStatus',
            ],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'idStore',
            'options' => [
                'label' => 'idStore',
            ],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'idUser',
            'options' => [
                'label' => 'idUser',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'assemblyCreatorName',
            'options' => [
                'label' => 'assemblyCreatorName',
            ],
        ]);
        $this->add([
            'type' => 'datetimelocal',
            'name' => 'deliveryDateTime',
            'options' => [
                'label' => 'deliveryDateTime',
            ],
        ]);
        $this->add([
            'type' => 'datetimelocal',
            'name' => 'expectedContactDateTime',
            'options' => [
                'label' => 'expectedContactDateTime',
            ],
        ]);
        $this->add([
            'type' => 'datetimelocal',
            'name' => 'expectedInstallationDateTime',
            'options' => [
                'label' => 'expectedInstallationDateTime',
            ],
        ]);
        $this->add([
            'type' => 'datetimelocal',
            'name' => 'acceptedInstallationDateTime',
            'options' => [
                'label' => 'acceptedInstallationDateTime',
            ],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'floorCarpetMeters',
            'options' => [
                'label' => 'floorCarpetMeters',
            ],
            'attributes' => ['step' => '0.01'],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'floorPanelMeters',
            'options' => [
                'label' => 'floorPanelMeters',
            ],
            'attributes' => ['step' => '0.01'],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'floorWoodMeters',
            'options' => [
                'label' => 'floorWoodMeters',
            ],
            'attributes' => ['step' => '0.01'],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'doorNumber',
            'options' => [
                'label' => 'doorNumber',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'notificationEmail',
            'options' => [
                'label' => 'notificationEmail',
            ],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'estimatedCostNet',
            'options' => [
                'label' => 'estimatedCostNet',
            ],
            'attributes' => ['step' => '0.01'],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'installationCity',
            'options' => [
                'label' => 'installationCity',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'installationAddress',
            'options' => [
                'label' => 'installationAddress',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'installationZipCode',
            'options' => [
                'label' => 'installationZipCode',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'installationName',
            'options' => [
                'label' => 'installationName',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'installationPhoneNumber',
            'options' => [
                'label' => 'installationPhoneNumber',
            ],
        ]);
        $this->add([
            'type' => 'email',
            'name' => 'installationEmail',
            'options' => [
                'label' => 'installationEmail',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'installationNote',
            'options' => [
                'label' => 'installationNote',
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'customer',
            'options' => [
                'label' => 'Sklep',
                'empty_option' => 'wybierz',
                'object_manager' => $objectManager,
                'target_class' => Customer::class,
                'property' => 'name',
                'is_method' => true,
                'find_method' => [
                    'name' => 'findAll',
                ],
            ],
            'attributes' => [
                'id' => 'customer',
            ],
        ]);

        $optional = ['required' => false];
        $defaultFilter = [
            ['name' => StringTrim::class],
            ['name' => StripTags::class],
        ];
        $required = [
            'required' => true,
        ];
        $requiredTxt = [
            'required' => true,
            'filters' => $defaultFilter,
        ];
        $email = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new EmailAddress(),
            ],
        ];
        $int = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new IsInt(['locale' => 'en']),
            ],
        ];
        $float = [
            'required' => false,
            'filters' => $defaultFilter,
            'validators' => [
                new IsFloat(),
            ],
        ];

        $this->inputFilter = [
            'idMeasurementOrder' => array_merge($int, $required),
            'idInstallationOrder' => $int,
            'idParentInstallationOrder' => $int,
            'measurementStatus' => $optional,
            'installationStatus' => $optional,
            'idStore' => array_merge($int, $required),
            'idUser' => $int,
            'assemblyCreatorName' => $optional,
            'deliveryDateTime' => $optional,
            'expectedContactDateTime' => $optional,
            'expectedInstallationDateTime' => $optional,
            'acceptedInstallationDateTime' => $optional,
            'floorCarpetMeters' => $float,
            'floorPanelMeters' => $float,
            'floorWoodMeters' => $float,
            'doorNumber' => $int,
            'notificationEmail' => $optional,
            'estimatedCostNet' => $float,
            'installationCity' => $requiredTxt,
            'installationAddress' => $requiredTxt,
            'installationZipCode' => $optional,
            'installationName' => $requiredTxt,
            'installationPhoneNumber' => $optional,
            'installationEmail' => $email,
            'installationNote' => $optional,
        ];
    }
}