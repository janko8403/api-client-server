<?php

namespace Configuration\Fieldset;

use Configuration\Entity\HolidayDate;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Element;
use Hr\Fieldset\BaseFieldset;

class HolidayDateFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('holidayDate');

        $this->setHydrator(new DoctrineHydrator($objectManager));
        $this->setObject(new HolidayDate());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => Element\Date::class,
            'name' => 'date',
            'options' => [
                'label' => 'Data',
            ],
            'attributes' => [
                'data-provide' => 'datepicker',
            ],
        ]);

        $this->inputFilter = [
            'date' => [
                'validators' => [],
            ],
        ];
    }
}