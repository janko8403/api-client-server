<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 11:29
 */

namespace Settings\Fieldset;


use Doctrine\Persistence\ObjectManager;
use Hr\Entity\DictionaryDetailsDescription;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

class DictionaryDetailsDescriptionFieldset extends Fieldset implements InputFilterAwareInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('dictionaryDetailDescription');
        $this->setHydrator(new DoctrineHydrator($objectManager, DictionaryDetailsDescription::class))->setObject(new DictionaryDetailsDescription());
        $this->add([
            'type' => 'text',
            'name' => DictionaryDetailsDescription::KEY_VARIABLE_1,
            'options' => [
                'label' => 'Zmienna 1',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => DictionaryDetailsDescription::KEY_VARIABLE_2,
            'options' => [
                'label' => 'Zmienna 2',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => DictionaryDetailsDescription::KEY_VARIABLE_3,
            'options' => [
                'label' => 'Zmienna 3',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => DictionaryDetailsDescription::KEY_VARIABLE_4,
            'options' => [
                'label' => 'Zmienna 4',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => DictionaryDetailsDescription::KEY_VARIABLE_5,
            'options' => [
                'label' => 'Zmienna 5',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => DictionaryDetailsDescription::KEY_VARIABLE_6,
            'options' => [
                'label' => 'Zmienna 6',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        $std = [
            'required' => true,
            'validators' => [
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ];

        return [
            'name' => $std,
            'isActive' => $std,
        ];
    }

    /**
     * Set input filter
     *
     * @param InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        // TODO: Implement setInputFilter() method.
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
    }
}