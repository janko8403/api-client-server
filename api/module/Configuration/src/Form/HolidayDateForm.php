<?php

namespace Configuration\Form;

use Configuration\Fieldset\HolidayDateFieldset;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Hr\Form\RecordForm;

class HolidayDateForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('holidayDateForm');

        $this->objectManager = $objectManager;

        $this->setAttribute('method', 'post');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $holidayDateFieldset = new HolidayDateFieldset($objectManager);
        $holidayDateFieldset->setUseAsBaseFieldset(true);
        $this->add($holidayDateFieldset);

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