<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 20.10.17
 * Time: 16:34
 */

namespace Users\Form;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Hr\Form\RecordForm;
use Users\Fieldset\ChangePasswordFieldset;

class ChangePasswordForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('changePasswordForm');

        $this->setAttribute('method', 'post');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $changePasswordFieldset = new ChangePasswordFieldset($objectManager, 'userPassword');

        $changePasswordFieldset->setUseAsBaseFieldset(true);
        $this->add($changePasswordFieldset);
        $this->setValidationGroup([
            'userPassword' => [
                'password',
                'orgPassword',
            ],
        ]);
        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz',
            ],
        ]);
    }

    public function setupSimple()
    {
        $this->remove('save');
        $this->get('userPassword')->remove('orgPassword');
        $this->setValidationGroup([
            'userPassword' => [
                'password',
            ],
        ]);
    }
}