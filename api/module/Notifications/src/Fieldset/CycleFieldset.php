<?php

namespace Notifications\Fieldset;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Fieldset\BaseFieldset;
use Notifications\Entity\Cycle;

class CycleFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('cycle');

        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new Cycle());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'delay',
            'options' => [
                'label' => 'Opóźnienie w minutach',
            ],
            'attributes' => ['step' => 0],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'display',
            'options' => [
                'label' => 'Wyświetlenie w minutach',
            ],
            'attributes' => ['step' => 0],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'rankingFrom',
            'options' => [
                'label' => 'Ranking od',
            ],
            'attributes' => ['step' => 0],
        ]);
        $this->add([
            'type' => 'number',
            'name' => 'rankingTo',
            'options' => [
                'label' => 'Ranking do',
            ],
            'attributes' => ['step' => 0],
        ]);

        $this->inputFilter = [
            'delay' => ['required' => true],
            'display' => ['required' => true],
            'rankingFrom' => ['required' => true],
            'rankingTo' => ['required' => true],
        ];
    }
}
