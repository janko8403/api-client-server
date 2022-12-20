<?php

namespace AssemblyOrders\Form;

use AssemblyOrders\Fieldset\AssemblyOrderFieldset;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\RecordForm;

class AssemblyOrderForm extends RecordForm
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('assemblyOrderForm');

        $fieldset = new AssemblyOrderFieldset($objectManager);
        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $this->add($fieldset);
        $this->setBaseFieldset($fieldset);

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