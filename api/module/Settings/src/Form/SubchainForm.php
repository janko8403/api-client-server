<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 04:51
 */

namespace Settings\Form;


use Doctrine\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Settings\Fieldset\SubchainFieldset;
use Hr\Form\RecordForm;

class SubchainForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('subchainForm');

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new SubchainFieldset($objectManager);

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