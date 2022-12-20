<?php

namespace Customers\Form;

use Customers\Fieldset\UserFieldset;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\RecordForm;

class UserForm extends RecordForm
{
    public function __construct(
        ObjectManager $objectManager
    )
    {
        parent::__construct('userForm');

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new UserFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz',
            ],
        ]);
    }
}