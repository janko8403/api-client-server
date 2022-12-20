<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 22.12.2016
 * Time: 23:42
 */

namespace Hr\Fieldset;

use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Hr\Dictionary\DictionaryService;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class BaseFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $inputFilter = [];

    /**
     * @var array
     */
    protected $regions;

    public function getInputFilterSpecification()
    {
        return $this->inputFilter;
    }

    public function setInputFilterSpecification($inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * @param array $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    /**
     * Gets standard multiselect config for dictionary details.
     *
     * @param ObjectManager $objectManager
     * @param string        $name
     * @param string        $label
     * @param int           $dictionaryId
     * @param bool          $multiple
     * @return array
     */
    protected function getDictionaryFieldConfig(
        ObjectManager $objectManager,
        string        $name,
        string        $label,
        int           $dictionaryId,
        bool          $multiple = true
    ): array
    {
        $criteria = ['dictionary' => $dictionaryId, 'isactive' => 1];
        if ($dictionaryId == Dictionary::DIC_REGIONS && !is_null($this->regions)) {
            $criteria['id'] = $this->regions;
        }

        $config = [
            'type' => ObjectSelect::class,
            'name' => $name,
            'attributes' => [
                'multiple' => $multiple,
                'id' => $name,
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => $label,
                'empty_option' => ($multiple) ? '' : 'Wybierz',
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'property' => 'name',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => $criteria,
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ];

        if ($childDictionary = DictionaryService::getChildDictionary($dictionaryId)) {
            $config['attributes']['data-child-dictionary-id'] = $childDictionary['id'];
            $config['attributes']['data-child-dictionary-name'] = $childDictionary['name'];
        }

        return $config;
    }

    /**
     * Sets select field add-on option (adds an ability to extend dictionary).
     *
     * @param string $field
     * @param int    $id
     */
    protected function addExtendDictionaryButton(string $field, int $id)
    {
        $this->get($field)->setOption(
            'add-on-append',
            [
                'element' => [
                    'type' => 'button',
                    'name' => 'add',
                    'attributes' => [
                        'class' => 'btn-sl btn-add add-dictionary-value',
                        'data-dictionary-name' => $field,
                        'data-dictionary-id' => $id,
                    ],
                    'options' => [
                        'label' => '<span class="fa fa-plus"></span>',
                        'label_options' => [
                            'disable_html_escape' => true,
                        ],
                    ],
                ],
            ]
        );
    }
}