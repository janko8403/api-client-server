<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.09.17
 * Time: 18:03
 */

namespace Settings\Form;

use Configuration\Entity\Position;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Laminas\Form\Form;

class CopyPrivilegesForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('copyPrivilages');

        $this->setAttribute('method', 'post');


        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'positionFrom',
            'attributes' => [
                'id' => 'positionFrom',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Kopiuj z',
                'object_manager' => $objectManager,
                'target_class'   => Position::class,
                'property' => 'name',
                'is_method' => false,
                'find_method'    => [
                    'name'   => 'findBy',
                    'params' => [
                        'criteria' => ['isActive' => true],
                        'orderBy'  => ['id' => 'DESC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'positionTo',
            'attributes' => [
                'id' => 'positionTo',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Kopiuj na',
                'object_manager' => $objectManager,
                'target_class'   => Position::class,
                'property' => 'name',
                'is_method' => false,
                'find_method'    => [
                    'name'   => 'findBy',
                    'params' => [
                        'criteria' => ['isActive' => true],
                        'orderBy'  => ['id' => 'DESC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz'
            ],
        ]);
    }
}
