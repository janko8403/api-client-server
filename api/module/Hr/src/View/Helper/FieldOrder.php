<?php

namespace Hr\View\Helper;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\View\Helper\AbstractHelper;

class FieldOrder extends AbstractHelper
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string
     */
    private $objectId;

    /**
     * @var array
     */
    private $fieldOrderCache;

    /**
     * FieldOrder constructor.
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $objectId
     * @return FieldOrder
     */
    public function setObjectId(string $objectId): FieldOrder
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function __invoke(string $fieldId, $objectId = null)
    {
        if (!empty($objectId)) {
            $this->objectId = $objectId;
        }

        if (empty($this->objectId)) {
            throw new \Exception("Object ID isn't set.");
        }

        if (empty($this->fieldOrderCache)) {
            // get field order from database
            $this->loadFieldOrder();
        }

        if (!isset($this->fieldOrderCache[$fieldId])) {
            // add field to field order
            $this->addFieldToFieldOrder($fieldId);
        }

        return $this->fieldOrderCache[$fieldId]['isVisible'];
    }

    private function loadFieldOrder()
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select('fieldOrder');
        $select->where([
            'objectId' => $this->objectId,
            'type' => 'table',
        ]);
        $select->order('order');

        $fields = [];
        $fieldsTmp = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);
        foreach ($fieldsTmp as $f) {
            $fields[$f['fieldId']] = $f->getArrayCopy();
        }

        $this->fieldOrderCache = $fields;
    }

    private function addFieldToFieldOrder(string $name)
    {
        $field = [
            'type' => 'table',
            'objectId' => $this->objectId,
            'fieldId' => $name,
            'isVisible' => 1,
            'isVisibleMobile' => 1,
            'isRequired' => 0,
            'name' => $name,
            'order' => 100,
        ];
        $this->fieldOrderCache[$name] = $field;

        $sql = new Sql($this->adapter);
        $insert = $sql->insert('fieldOrder');
        $insert->values($field);

        $this->adapter->query($sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE);
    }
}