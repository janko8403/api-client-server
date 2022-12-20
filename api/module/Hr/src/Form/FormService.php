<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 22.12.2016
 * Time: 17:45
 */

namespace Hr\Form;

use Configuration\Entity\ObjectGroup;
use Configuration\Object\ObjectService;
use Settings\Service\FieldDisablerService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class FormService
{
    /**
     * @var ObjectService
     */
    private $objectService;

    /**
     * @var string
     */
    private $route;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * @var BaseForm
     */
    private $form;

    /**
     * @var FieldDisablerService
     */
    private $fieldDisablerService;

    /**
     * FormService constructor.
     *
     * @param ObjectService        $objectService
     * @param AbstractAdapter      $cacheAdapter
     * @param FieldDisablerService $fieldDisablerService
     * @param string               $route
     */
    public function __construct(
        ObjectService        $objectService,
        AbstractAdapter      $cacheAdapter,
        FieldDisablerService $fieldDisablerService,
        string               $route
    )
    {
        $this->objectService = $objectService;
        $this->route = $route;
        $this->cacheAdapter = $cacheAdapter;
        $this->fieldDisablerService = $fieldDisablerService;
    }

    /**
     * Processed passed form param.
     * Sets labels, field order, visibility.
     *
     * @param BaseForm $form
     * @throws \Exception
     */
    public function process(BaseForm $form)
    {
        $this->form = $form;

        if ($form instanceof RecordForm) {
            $type = ObjectGroup::TYPE_FORM;
        } elseif ($form instanceof SearchForm) {
            $type = ObjectGroup::TYPE_SEARCH;
        } else {
            throw new \Exception('Unknown form type `' . get_class($form) . '`');
        }

        // get fields in form
        $formFields = $this->getFieldsFromForm($form);

        // check if all fields should be disabled
        $disableAllFields = $this->fieldDisablerService->shouldDisableAllFields(get_class($form));

        $cacheKey = $this->objectService->generateObjectCacheKey($type, $form->getName());
        if ($this->cacheAdapter->hasItem($cacheKey)) {
            $fields = $this->cacheAdapter->getItem($cacheKey);
        } else {
            $object = $this->objectService->getAndFillObjectDetails(
                $type,
                $form->getName(),
                array_keys($formFields),
                array_column($formFields, 'label')
            );
            $fields = $this->objectService->getDeviceFields($object->getFields()->toArray());
            $this->cacheAdapter->setItem($cacheKey, $fields);
        }

        $diff = array_diff(array_keys($formFields), array_keys($fields));
        $diff = array_flip($diff);

        foreach ($formFields as $fieldName => $formField) {
            $fieldPath = $formField['path'];

            /**
             * @var Fieldset $parentFieldset
             * @var Element  $currentField
             */
            extract($this->extractFieldAndParentFromForm($form, $fieldPath));

            if ($parentFieldset instanceof Element\Collection) {
                $parentFieldset = $parentFieldset->getTargetElement();
            }

            if (isset($diff[$fieldName])) {
                $this->removeField($parentFieldset, $fieldName);
                continue;
            }

            // set field order
            if ($parentFieldset->has($fieldName)) {
                $parentFieldset->getIterator()->setPriority($fieldName, 1000 - $fields[$fieldName]['order']);
            }

            if ($form instanceof RecordForm) {
                $form->addFieldOrder($fieldPath, $fields[$fieldName]['order']);
            }

            // set label
            if (!empty($fields[$fieldName]['label'])) {
                $currentField->setLabel($fields[$fieldName]['label']);
            }

            // set disabled
            if ($fields[$fieldName]['disabled'] || $disableAllFields) {
                $currentField->setAttribute('disabled', true);
                $inputFilter = $parentFieldset->getInputFilterSpecification();
                $inputFilter[$fieldName]['required'] = false;
                $parentFieldset->setInputFilterSpecification($inputFilter);
            }

            if ($fields[$fieldName]['disabled_if_not_empty']) {
                if ($id = $currentField->getAttribute('id')) {
                    $form->addDisabledIfNotEmpty($id);
                }
            }

            // remove add to dictionary button
            if (
                !$fields[$fieldName]['display_add_dictionary']
                && $currentField instanceof Element\Select
                && $currentField->getOption('add-on-append')
            ) {
                $currentField->setOption('add-on-append', null);
            }
        }
    }

    /**
     * Sets given form element as optional (is required by default).
     *
     * @param Fieldset $parentFieldset
     * @param Element  $currentField
     */
    public function setFieldAsOptional(Fieldset $parentFieldset, Element $currentField)
    {
        $inputFilter = $parentFieldset->getInputFilterSpecification();
        $inputFilter[$currentField->getName()]['required'] = false;
        $parentFieldset->setInputFilterSpecification($inputFilter);
    }

    /**
     * Gets flat array with field name with theirs paths (fieldset\fieldset\element etc.)
     *
     * @param Fieldset    $form
     * @param string|null $name
     * @return array
     */
    private function getFieldsFromForm($form, string $name = null): array
    {
        $fields = [];

        foreach ($form as $f) {
            if ($f instanceof Fieldset) {
                $nameCombined = $name ? $name . '\\' . $f->getName() : '' . $f->getName();

                $fieldsContainer = ($f instanceof Element\Collection) ? $f->getTargetElement() : $f;

                $fields = array_merge($fields, $this->getFieldsFromForm($fieldsContainer, $nameCombined));
            } else if (!($f instanceof Element\Submit) && !($f instanceof Element\Button)) {
                $fields[$f->getName()] = [
                    'path' => ($name ? $name . "\\" : '') . $f->getName(),
                    'label' => $f->getLabel(),
                ];
            }
        }

        return $fields;
    }

    /**
     * Extracts form element instance and parent fieldset instance from given path.
     *
     * @param BaseForm $form
     * @param string   $field
     * @return array
     */
    private function extractFieldAndParentFromForm(BaseForm $form, string $field): array
    {
        $parts = explode('\\', $field);
        $currentField = $form;
        $parentFieldset = null;

        while (count($parts)) {
            $p = array_shift($parts);
            $parentFieldset = $currentField;

            if ($currentField instanceof Element\Collection) {
                $currentField = $currentField->getTargetElement()->get($p);
            } else {
                $currentField = $currentField->get($p);
            }
        }

        return ['parentFieldset' => $parentFieldset, 'currentField' => $currentField];
    }

    /**
     * Removes given form element from a fieldset.
     *
     * @param Fieldset $parentFieldset
     * @param          $fieldToRemove
     */
    private function removeField(Fieldset $parentFieldset, $fieldToRemove)
    {
        $name = ($fieldToRemove instanceof Element) ? $fieldToRemove->getName() : $fieldToRemove;

        $parentFieldset->remove($name);

        if ($parentFieldset instanceof InputFilterProviderInterface) {
            $inputFilter = $parentFieldset->getInputFilterSpecification();
            if (isset($inputFilter[$name])) {
                unset($inputFilter[$name]);
                $parentFieldset->setInputFilterSpecification($inputFilter);
            }
        }
    }
}