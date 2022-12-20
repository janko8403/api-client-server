<?php


namespace Customers\Form;


use Customers\Fieldset\UserFieldset;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\RecordForm;
use Laminas\InputFilter\InputFilterProviderInterface;
use Users\Fieldset\UserFieldset as AddUserFieldset;

class AddUserForm extends RecordForm implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('addUserForm');

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $userFieldset = new AddUserFieldset($objectManager, 'userFieldset');
        $this->add($userFieldset->get('name'));
        $this->add($userFieldset->get('surname'));
        $this->add($userFieldset->get('email'));
        $this->add($userFieldset->get('phonenumber'));
        $this->add($userFieldset->get('position'));

        $fieldset = new UserFieldset($objectManager);
        $this->add($fieldset->get('relation'));

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => ['required' => true],
            'surname' => ['required' => true],
            'email' => ['required' => false],
            'phonenumber' => ['required' => false],
        ];
    }
}