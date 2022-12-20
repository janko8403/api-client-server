<?php

namespace Configuration\Form;

use Configuration\Fieldset\MonitoringCategoryFieldset;
use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Form;

class MonitoringCategoryForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('monitoringCategoryForm');

        $this->setAttribute('method', 'post');
        $monitoringCategoryFieldset = new MonitoringCategoryFieldset($objectManager);
        $monitoringCategoryFieldset->setUseAsBaseFieldset(true);
        $this->add($monitoringCategoryFieldset);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz'
            ],
        ]);
    }
}