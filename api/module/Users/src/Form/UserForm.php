<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.10.17
 * Time: 15:01
 */

namespace Users\Form;


use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\RecordForm;
use Users\Fieldset\UserFieldset;

class UserForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('userForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'editForm');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $userFieldset = new UserFieldset($objectManager, 'user');
        $userFieldset->setUseAsBaseFieldset(true);
        $this->add($userFieldset);

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