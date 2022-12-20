<?php

namespace Settings\Entity;

/**
 * Dictionary
 */
class Dictionary
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
     * @var string
     */
    private $entityName;

    /**
     * @var boolean
     */
    private $isMultiLevel;


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
     * Set name
     *
     * @param string $name
     *
     * @return Dictionary
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set entityName
     *
     * @param string $entityName
     *
     * @return Dictionary
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set isMultiLevel
     *
     * @param boolean $isMultiLevel
     *
     * @return Dictionary
     */
    public function setIsMultiLevel($isMultiLevel)
    {
        $this->isMultiLevel = $isMultiLevel;

        return $this;
    }

    /**
     * Get isMultiLevel
     *
     * @return boolean
     */
    public function getIsMultiLevel()
    {
        return $this->isMultiLevel;
    }
}

