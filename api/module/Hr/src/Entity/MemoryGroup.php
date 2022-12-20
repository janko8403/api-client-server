<?php

namespace Hr\Entity;

/**
 * MemoryGroup
 */
class MemoryGroup
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $startUrl;


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
     * @return MemoryGroup
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return MemoryGroup
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return MemoryGroup
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get startUrl
     *
     * @return string
     */
    public function getStartUrl()
    {
        return $this->startUrl;
    }

    /**
     * Set startUrl
     *
     * @param string $startUrl
     *
     * @return MemoryGroup
     */
    public function setStartUrl($startUrl)
    {
        $this->startUrl = $startUrl;

        return $this;
    }
}

