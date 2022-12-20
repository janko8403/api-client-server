<?php

namespace Hr\Form;

use Hr\Entity\RecordPickerFilter;

abstract class RecordForm extends BaseForm
{
    const TYPE = 'form';

    private $fieldOrder = [];

    public function __construct($name, array $options = [])
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'POST');
    }

    /**
     * @return array
     */
    public function getFieldOrder(): array
    {
        ksort($this->fieldOrder);

        return $this->fieldOrder;
    }

    public function addFieldOrder(string $path, int $order)
    {
        $this->fieldOrder[$order] = $path;
    }

    /**
     * Gets number of records selected in a specified record picker and set `count` option.
     *
     * @param int    $userId    User id
     * @param string $filterId  Search form id
     * @param string $fieldName Form's record picker field id (can contain field path ex. fieldset/fieldset/fieldName)
     */
    public function setRecordPickerCount($userId, $filterId, $fieldName)
    {
        $elements = explode('/', $fieldName);
        $current = $this;
        $found = false;

        while (count($elements)) {
            $element = array_shift($elements);

            if ($current->has($element)) {
                $current = $current->get($element);
                $found = true;
            }
        }

        if (!$found) {
            return;
        }

        $records = $this->objectManager->getRepository(RecordPickerFilter::class)->findRecordsArray(
            $userId, $filterId
        );
        $current->setOption('count', count($records));
    }
}