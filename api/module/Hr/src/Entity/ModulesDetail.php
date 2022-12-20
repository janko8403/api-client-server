<?php

namespace Hr\Entity;

/**
 * ModulesDetail
 */
class ModulesDetail
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var integer
     */
    private $previousId;

    /**
     * @var boolean
     */
    private $isActive = '0';

    /**
     * @var integer
     */
    private $order = '0';

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Hr\Entity\MemoryModule
     */
    private $module;


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
     * Get previousId
     *
     * @return integer
     */
    public function getPreviousId()
    {
        return $this->previousId;
    }

    /**
     * Set previousId
     *
     * @param integer $previousId
     *
     * @return ModulesDetail
     */
    public function setPreviousId($previousId)
    {
        $this->previousId = $previousId;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ModulesDetail
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return ModulesDetail
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

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
     * @return ModulesDetail
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get module
     *
     * @return \Hr\Entity\MemoryModule
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set module
     *
     * @param \Hr\Entity\MemoryModule $module
     *
     * @return ModulesDetail
     */
    public function setModule(\Hr\Entity\MemoryModule $module = null)
    {
        $this->module = $module;

        return $this;
    }
}

