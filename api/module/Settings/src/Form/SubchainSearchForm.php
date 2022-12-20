<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 09:09
 */

namespace Settings\Form;

use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Entity\DictionaryDetails;
use Hr\Form\SearchForm;

class SubchainSearchForm extends SearchForm
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('subchainsSearch');

        $this->add([
            'type' => 'hidden',
            'name' => 'perPage',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'chain',
            'attributes' => [
                'multiple' => true,
                'id' => 'chain',
            ],
            'options' => [
                'label' => 'Sieć',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'label_generator' => function (DictionaryDetails $targetEntity) {
                    return $targetEntity->getName();
                },
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => \Hr\Entity\Dictionary::DIC_CHAINS, 'isactive' => 1],
                    ],
                ],
            ],
        ]);
        $this->add([
            'type' => 'submit',
            'name' => 'search',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Szukaj',
            ],
        ]);
    }

}