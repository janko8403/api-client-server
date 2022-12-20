<?php

namespace DocumentTemplates\Fieldset;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\DocumentTemplate;
use Monitorings\Entity\KnowledgeLevel;
use Hr\Fieldset\BaseFieldset;

class DocumentTemplateFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('documentTemplate');

        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new DocumentTemplate());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Typ',
                'value_options' => DocumentTemplate::getTypes(),
                'empty_option' => 'wybierz',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'contentHeader',
            'options' => [
                'label' => 'Nagłówek',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'contentBody',
            'options' => [
                'label' => 'Treść',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);
        $this->add([
            'type' => 'textarea',
            'name' => 'contentFooter',
            'options' => [
                'label' => 'Stopka',
            ],
            'attributes' => [
                'class' => 'summernote',
            ],
        ]);


        $optional = ['required' => false];

        $this->inputFilter = [
            'name' => [
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'type' => ['required' => false],
            'contentHeader' => $optional,
            'contentBody' => $optional,
            'contentFooter' => $optional,
        ];
    }
}
