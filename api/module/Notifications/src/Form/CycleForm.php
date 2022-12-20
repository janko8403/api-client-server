<?php

namespace Notifications\Form;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\RecordForm;
use Notifications\Fieldset\CycleFieldset;

class CycleForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('notificationCycleForm');

        $this->setAttribute('method', 'post');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new CycleFieldset($objectManager);
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