<?php

namespace Configuration\Entity;
use Doctrine\Common\Collections\Criteria;

/**
 * ObjectField
 */
class ObjectField
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
    private $label;

    /**
     * @var integer
     */
    private $sequence;

    /**
     * @var boolean
     */
    private $isActive = true;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $details;

    /**
     * @var \Configuration\Entity\ObjectGroup
     */
    private $object;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->details = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ObjectField
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
     * Set label
     *
     * @param string $label
     *
     * @return ObjectField
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return ObjectField
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ObjectField
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
     * Add detail
     *
     * @param \Configuration\Entity\ObjectFieldDetail $detail
     *
     * @return ObjectField
     */
    public function addDetail(\Configuration\Entity\ObjectFieldDetail $detail)
    {
        $this->details[] = $detail;

        return $this;
    }

    /**
     * Remove detail
     *
     * @param \Configuration\Entity\ObjectFieldDetail $detail
     */
    public function removeDetail(\Configuration\Entity\ObjectFieldDetail $detail)
    {
        $this->details->removeElement($detail);
    }

    /**
     * Get details
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set object
     *
     * @param \Configuration\Entity\ObjectGroup $object
     *
     * @return ObjectField
     */
    public function setObject(\Configuration\Entity\ObjectGroup $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \Configuration\Entity\ObjectGroup
     */
    public function getObject()
    {
        return $this->object;
    }

    /////////////////////////////////////////

    public function getDetailForPosition(Position $position) : ObjectFieldDetail
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('position', $position));
        $details  = $this->getDetails()->matching($criteria);

        if ($details->count() == 0) {
            throw new \Exception("No field details for position `{$position->getName()}`");
        }

        return $details->first();
    }
}

