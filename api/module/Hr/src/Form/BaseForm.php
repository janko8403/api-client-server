<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 25.10.2016
 * Time: 17:48
 */

namespace Hr\Form;

use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Settings\Service\PositionVisibilityService;
use Hr\Entity\DictionaryDetails;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\Element\Submit;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;

abstract class BaseForm extends Form
{
    use ProvidesObjectManager;

    const SPLIT_2_10 = '2:10';
    const SPLIT_4_8 = '4:8';

    /**
     * @var PositionVisibilityService
     */
    protected $positionVisibilityService;

    /**
     * @var array
     */
    protected $identity;

    /**
     * Ids of form fields to disable if not empty.
     *
     * @var array
     */
    private $disableIfNotEmpty = [];

    /**
     * @param PositionVisibilityService $positionVisibilityService
     */
    public function setPositionVisibilityService(PositionVisibilityService $positionVisibilityService)
    {
        $this->positionVisibilityService = $positionVisibilityService;
    }

    /**
     * @param array $identity
     */
    public function setIdentity(array $identity)
    {
        $this->identity = $identity;
    }

    public function addDisabledIfNotEmpty(string $fieldName)
    {
        $this->disableIfNotEmpty[] = $fieldName;
    }

    public function disabledIfNotEmpty(string $id): bool
    {
        return in_array($id, $this->disableIfNotEmpty);
    }

    /**
     * Prepares the form to be displayed horizontally.
     * Iteratef through form fields (recursively) and set column options.
     *
     * @param      $form
     * @param null $split Horizontal form columns split (ratio)
     */
    public function setColumnLayout($form = null, $split = null)
    {
        if (is_null($form)) {
            $iterate = $this;
        } else {
            $iterate = $form;
        }

        $split = $split ?? self::SPLIT_2_10;
        [$labelWidth, $colWidth] = explode(':', $split);

        foreach ($iterate as $elem) {
            if ($elem instanceof Fieldset) {
                $this->setColumnLayout($elem, $split);
            }

            if ($elem instanceof Collection) {
                $this->setColumnLayout($elem->getTargetElement(), $split);
            }

            if ($elem instanceof Submit || $elem instanceof Hidden) {
                $elem->setOption('column-size', 'md-12 text-right');
                $elem->setOption('twb-layout', 'horizontal');
            } elseif (
                ($elem instanceof Checkbox || $elem instanceof Button)
                && !($elem instanceof MultiCheckbox)
            ) {
                $elem->setOption('column-size', "md-$colWidth col-md-offset-$labelWidth");
                $elem->setOption('twb-layout', 'horizontal');
            } else {
                if (!$elem->getOption('column-size')) {
                    $elem->setOption('column-size', "md-$colWidth");
                    $elem->setOption('twb-layout', 'horizontal');
                    $elem->setLabelAttributes(['class' => "col-md-$labelWidth text-left"]);
                }
            }
        }
    }

    /**
     * Gets standard multiselect config for dictionary details.
     *
     * @param ObjectManager $objectManager
     * @param string        $name
     * @param string        $label
     * @param int           $dictionaryId
     * @param bool          $multiple
     * @param bool          $emptyOption
     * @return array
     */
    public function getDictionaryFieldConfig(
        ObjectManager $objectManager,
        string        $name,
        string        $label,
        int           $dictionaryId,
        bool          $multiple = true,
        bool          $emptyOption = false
    ): array
    {
        return [
            'type' => ObjectSelect::class,
            'name' => $name,
            'attributes' => [
                'multiple' => $multiple,
                'id' => $name,
                'style' => 'width:100%',
            ],
            'options' => [
                'label' => $label,
                'object_manager' => $objectManager,
                'target_class' => DictionaryDetails::class,
                'property' => 'name',
                'empty_option' => $emptyOption ? 'wybierz' : null,
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => ['dictionary' => $dictionaryId, 'isactive' => 1],
                        'orderBy' => ['name' => 'ASC'],
                    ],
                ],
            ],
        ];
    }

    protected function getRegionsVisibility(string $field)
    {
        return $this->positionVisibilityService->getListOfRegions(
            $field,
            $this->identity['configurationPositionId'],
            $this->identity['regionDicId'] ?? 0,
            $this->identity['supervisorId'],
            $this->identity['id']
        );
    }
}