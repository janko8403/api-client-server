<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 04:40
 */

namespace Settings\Fieldset;


use Doctrine\Persistence\ObjectManager;

use Hr\Entity\Dictionary;
use Hr\Entity\Subchain;
use Hr\Fieldset\BaseFieldset;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

class SubchainFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('subchain');

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager, Subchain::class))->setObject(new Subchain());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'isActive',
            'options' => [
                'label' => 'Czy aktywny',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);
        $this->add($this->getDictionaryFieldConfig($objectManager, 'chain', 'Sieć', Dictionary::DIC_CHAINS, false));
        $this->addExtendDictionaryButton('chain', Dictionary::DIC_CHAINS);
        $this->add([
            'type' => 'text',
            'name' => 'variable1',
            'options' => [
                'label' => 'Zmienna 1',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'variable2',
            'options' => [
                'label' => 'Zmienna 2',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'variable3',
            'options' => [
                'label' => 'Zmienna 3',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'variable4',
            'options' => [
                'label' => 'Zmienna 4',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'variable5',
            'options' => [
                'label' => 'Zmienna 5',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'variable6',
            'options' => [
                'label' => 'Zmienna 6',
            ],
        ]);

        $optional = ['required' => false];
        $required = ['required' => true];
        $this->inputFilter = [
            'name' => $required,
            'chain' => $required,
            'isActive' => $optional,
            'variable1' => $optional,
            'variable6' => $optional,
            'variable2' => $optional,
            'variable3' => $optional,
            'variable4' => $optional,
            'variable5' => $optional,
        ];
    }
}