<?php

namespace Notifications\Form;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Notifications\Fieldset\NotificationFieldset;
use Hr\Form\RecordForm;

class NotificationForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('notificationForm');

        $this->setAttribute('method', 'post');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new NotificationFieldset($objectManager);
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