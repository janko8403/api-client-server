<?php

namespace Customers\Fieldset;

use Customers\Entity\UserRelation;
use Customers\Entity\UserRelationJoint;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Fieldset\BaseFieldset;
use Users\Entity\User;

class UserFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('user');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new UserRelationJoint());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'user',
            'options' => [
                'label' => 'Użytkownik',
                'empty_option' => 'wybierz',
                'object_manager' => $objectManager,
                'target_class' => User::class,
                'property' => 'fullNameWithSAP',
                'is_method' => true,
                'find_method' => [
                    'name' => 'fetchAllActive',
                ],
            ],
            'attributes' => [
                'id' => 'user',
            ],
        ]);
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'relation',
            'options' => [
                'label' => 'Relacja',
                'empty_option' => 'wybierz',
                'object_manager' => $objectManager,
                'target_class' => UserRelation::class,
                'property' => 'name',
                'find_method' => [
                    'name' => 'findAll',
                ],
            ],
        ]);

        $this->inputFilter = [
        ];
    }
}
