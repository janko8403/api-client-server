<?php

namespace Hr\Entity;

/**
 * SystemSetting
 */
class SystemSetting
{
    const TYPE_WHOLESALER_STATE_SYNC_ORDER_STATUSES = 'wholesalerStateSyncOrderStatuses';
    const TYPE_WHOLESALER_STATE_SYNC_PRODUCT_SUBGROUPS = 'wholesalerStateSyncProductSubgroups';

    const TYPE_TIKROW_VACATION_FROM = 'tikrowVacationFrom';
    const TYPE_TIKROW_VACATION_TO = 'tikrowVacationTo';

    const SCORING_CAT_1 = 'scoringCategory1';
    const SCORING_CAT_2 = 'scoringCategory2';
    const SCORING_CAT_3 = 'scoringCategory3';
    const SCORING_CAT_4 = 'scoringCategory4';
    const SCORING_CAT_5 = 'scoringCategory5';

    const SCORING_DAYS = 'scoringDays';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $selectTable;

    /**
     * @var string
     */
    private $selectQuery;

    /**
     * @var string
     */
    private $selectKey;

    /**
     * @var string
     */
    private $selectValue;

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SystemSetting
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return SystemSetting
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return SystemSetting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get selectTable
     *
     * @return string
     */
    public function getSelectTable()
    {
        return $this->selectTable;
    }

    /**
     * Set selectTable
     *
     * @param string $selectTable
     *
     * @return SystemSetting
     */
    public function setSelectTable($selectTable)
    {
        $this->selectTable = $selectTable;

        return $this;
    }

    /**
     * Get selectQuery
     *
     * @return string
     */
    public function getSelectQuery()
    {
        return $this->selectQuery;
    }

    /**
     * Set selectQuery
     *
     * @param string $selectQuery
     *
     * @return SystemSetting
     */
    public function setSelectQuery($selectQuery)
    {
        $this->selectQuery = $selectQuery;

        return $this;
    }

    /**
     * Get selectKey
     *
     * @return string
     */
    public function getSelectKey()
    {
        return $this->selectKey;
    }

    /**
     * Set selectKey
     *
     * @param string $selectKey
     *
     * @return SystemSetting
     */
    public function setSelectKey($selectKey)
    {
        $this->selectKey = $selectKey;

        return $this;
    }

    /**
     * Get selectValue
     *
     * @return string
     */
    public function getSelectValue()
    {
        return $this->selectValue;
    }

    /**
     * Set selectValue
     *
     * @param string $selectValue
     *
     * @return SystemSetting
     */
    public function setSelectValue($selectValue)
    {
        $this->selectValue = $selectValue;

        return $this;
    }
}

