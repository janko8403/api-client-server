<?php

namespace Configuration\Entity;

/**
 * Object
 */
class ObjectGroup
{
    const TYPE_SEARCH = 1;
    const TYPE_TABLE = 2;
    const TYPE_FORM = 3;

    public static function getTypes()
    {
        return [
            self::TYPE_SEARCH => 'wyszukiwarka',
            self::TYPE_TABLE => 'tabela',
            self::TYPE_FORM => 'formularz',
        ];
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var boolean
     */
    private $isActive = true;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fields;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $resources;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resources = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return ObjectGroup
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
     * Set type
     *
     * @param integer $type
     *
     * @return ObjectGroup
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ObjectGroup
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

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
     * Add field
     *
     * @param \Configuration\Entity\ObjectField $field
     *
     * @return ObjectGroup
     */
    public function addField(\Configuration\Entity\ObjectField $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Remove field
     *
     * @param \Configuration\Entity\ObjectField $field
     */
    public function removeField(\Configuration\Entity\ObjectField $field)
    {
        $this->fields->removeElement($field);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Add resource
     *
     * @param \Configuration\Entity\Resource $resource
     *
     * @return ObjectGroup
     */
    public function addResource(\Configuration\Entity\Resource $resource)
    {
        $this->resources[] = $resource;

        return $this;
    }

    /**
     * Remove resource
     *
     * @param \Configuration\Entity\Resource $resource
     */
    public function removeResource(\Configuration\Entity\Resource $resource)
    {
        $this->resources->removeElement($resource);
    }

    /**
     * Get resources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResources()
    {
        return $this->resources;
    }

    ///////////////////////////////////

    public function getFieldByName(string $name)
    {
        return $this->fields[$name] ?? null;
    }
}
