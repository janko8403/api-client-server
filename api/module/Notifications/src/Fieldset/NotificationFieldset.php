<?php

namespace Notifications\Fieldset;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Fieldset\BaseFieldset;
use Notifications\Entity\Notification;

class NotificationFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('notification');

        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new Notification());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Typ',
                'value_options' => Notification::TYPES,
                'empty_option' => 'wybierz',
            ],
            'attributes' => [
                'id' => 'notification-type',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'subject',
            'options' => [
                'label' => 'Temat',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'content',
            'options' => [
                'label' => 'Treść',
            ],
            'attributes' => [
                'id' => 'notification-content',
            ],
        ]);

        $this->inputFilter = [
            'subject' => ['required' => true],
            'content' => ['required' => true],
            'type' => ['required' => true],
            'instance' => ['required' => true],
        ];
    }
}
