<?php

namespace Settings\Entity;

/**
 * PositionVisibility
 */
class PositionVisibility
{
    const TYPE_ALL = 1;
    const TYPE_MACROREGION = 2;
    const TYPE_SUBREGION = 3;
    const TYPE_REGION = 4;
    const TYPE_SUPERIOR = 5;
    const TYPE_SUBORDINATES = 6;

    const FIELD_CUSTOMERS = 'customers';
    const FIELD_CUSTOMERS_EDIT = 'customersEdit';
    const FIELD_CUSTOMERS_PICKER = 'customersPicker';
    const FIELD_USERS = 'users';
    const FIELD_ORDERS = 'orders';
    const FIELD_MONITORINGS = 'monitorings';
    const FIELD_MONITORINGS_FULFILL = 'monitoringsFulfill';
    const FIELD_COMMISSIONS = 'commissions';

    public static function getTypes()
    {
        return [
            self::TYPE_ALL => 'Wszystko',
            self::TYPE_MACROREGION => 'Mój makroregion',
            self::TYPE_SUBREGION => 'Mój subregion',
            self::TYPE_REGION => 'Mój region',
            self::TYPE_SUPERIOR => 'Mój przełożony',
            self::TYPE_SUBORDINATES => 'Moi podwładni',
        ];
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $customers;

    /**
     * @var integer
     */
    private $customersEdit;

    /**
     * @var integer
     */
    private $customersPicker;

    /**
     * @var integer
     */
    private $users;

    /**
     * @var integer
     */
    private $orders;

    /**
     * @var integer
     */
    private $monitorings;

    /**
     * @var \Configuration\Entity\Position
     */
    private $position;

    /**
     * @var integer
     */
    private $commissions;

    /**
     * @var integer
     */
    private $monitoringsFulfill;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customers
     *
     * @param integer $customers
     *
     * @return PositionVisibility
     */
    public function setCustomers($customers)
    {
        $this->customers = $customers;

        return $this;
    }

    /**
     * Get customers
     *
     * @return integer
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @return int
     */
    public function getCustomersEdit()
    {
        return $this->customersEdit;
    }

    /**
     * @param int $customersEdit
     * @return PositionVisibility
     */
    public function setCustomersEdit(int $customersEdit): PositionVisibility
    {
        $this->customersEdit = $customersEdit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomersPicker()
    {
        return $this->customersPicker;
    }

    /**
     * @param int $customersPicker
     * @return PositionVisibility
     */
    public function setCustomersPicker(int $customersPicker): PositionVisibility
    {
        $this->customersPicker = $customersPicker;
        return $this;
    }

    /**
     * Set users
     *
     * @param integer $users
     *
     * @return PositionVisibility
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return integer
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set orders
     *
     * @param integer $orders
     *
     * @return PositionVisibility
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * Get orders
     *
     * @return integer
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set monitorings
     *
     * @param integer $monitorings
     *
     * @return PositionVisibility
     */
    public function setMonitorings($monitorings)
    {
        $this->monitorings = $monitorings;

        return $this;
    }

    /**
     * Get monitorings
     *
     * @return integer
     */
    public function getMonitorings()
    {
        return $this->monitorings;
    }

    /**
     * Set position
     *
     * @param \Configuration\Entity\Position $position
     *
     * @return PositionVisibility
     */
    public function setPosition(\Configuration\Entity\Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Configuration\Entity\Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set commissions
     *
     * @param integer $commissions
     *
     * @return PositionVisibility
     */
    public function setCommissions($commissions)
    {
        $this->commissions = $commissions;

        return $this;
    }

    /**
     * Get commissions
     *
     * @return integer
     */
    public function getCommissions()
    {
        return $this->commissions;
    }

    /**
     * @return int
     */
    public function getMonitoringsFulfill(): int
    {
        return $this->monitoringsFulfill;
    }

    /**
     * @param int $monitoringsFulfill
     * @return PositionVisibility
     */
    public function setMonitoringsFulfill(int $monitoringsFulfill)
    {
        $this->monitoringsFulfill = $monitoringsFulfill;
        return $this;
    }


    /////////////////////////////////////////////////

    public function get(string $field) : int
    {
        $method = 'get' . ucfirst($field);

        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            throw new \Exception("Unknown field `$field`");
        }
    }
}