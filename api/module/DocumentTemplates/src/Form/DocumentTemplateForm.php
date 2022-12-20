<?php

namespace DocumentTemplates\Form;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use DocumentTemplates\Fieldset\DocumentTemplateFieldset;
use Hr\Form\RecordForm;

class DocumentTemplateForm extends RecordForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('documentTemplateForm');

        $this->setAttribute('method', 'post');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $documentTemplateFieldset = new DocumentTemplateFieldset($objectManager);
        $documentTemplateFieldset->setUseAsBaseFieldset(true);
        $this->add($documentTemplateFieldset);

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