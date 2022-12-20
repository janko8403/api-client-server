<?php

namespace Hr\Entity;

/**
 * DictionaryDetails
 */
class DictionaryDetails
{
    const AGGLOMERATION_POLAND = 'agglomerationPoland';

    /**
     * @var integer
     */
    private $id;


    /**
     * @var boolean
     */
    private $isactive = '1';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var \Hr\Entity\Dictionary
     */
    private $dictionary;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $descriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->descriptions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get isactive
     *
     * @return boolean
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     *
     * @return DictionaryDetails
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

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
     * @return DictionaryDetails
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get dictionary
     *
     * @return \Hr\Entity\Dictionary
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * Set dictionary
     *
     * @param \Hr\Entity\Dictionary $dictionary
     *
     * @return DictionaryDetails
     */
    public function setDictionary(\Hr\Entity\Dictionary $dictionary = null)
    {
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * Add child
     *
     * @param \Hr\Entity\DictionaryDetails $child
     *
     * @return DictionaryDetails
     */
    public function addChild(\Hr\Entity\DictionaryDetails $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Hr\Entity\DictionaryDetails $child
     */
    public function removeChild(\Hr\Entity\DictionaryDetails $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return DictionaryDetails
     */
    public function setKey($key): DictionaryDetails
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Hr\Entity\DictionaryDetails $parent
     *
     * @return DictionaryDetails
     */
    public function setParent(\Hr\Entity\DictionaryDetails $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Add description
     *
     * @param \Hr\Entity\DictionaryDetailsDescription $description
     *
     * @return DictionaryDetails
     */
    public function addDescription(\Hr\Entity\DictionaryDetailsDescription $description)
    {
        $this->descriptions[] = $description;

        return $this;
    }

    /**
     * Remove description
     *
     * @param \Hr\Entity\DictionaryDetailsDescription $description
     */
    public function removeDescription(\Hr\Entity\DictionaryDetailsDescription $description)
    {
        $this->descriptions->removeElement($description);
    }

    /**
     * Get descriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }
}
