<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 13.07.2018
 * Time: 12:43
 */

namespace Settings\Form;

use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Laminas\Form\Form;

class RegionAssignForm extends Form
{
    /**
     * SubregionAssignForm constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('assign-form');

        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'region',
            'attributes' => [
                'id' => 'region',
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => 'Subregion',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'property' => 'name',
                'empty_option' => '(nieprzypisany)',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['isactive' => true, 'dictionary' => Dictionary::DIC_SUBREGIONS],
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ]);
    }
}