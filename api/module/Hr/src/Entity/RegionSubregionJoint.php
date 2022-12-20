<?php

namespace Hr\Entity;

/**
 * RegionSubregionJoint
 */
class RegionSubregionJoint
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $region;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $subregion;


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
     * Get region
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set region
     *
     * @param \Hr\Entity\DictionaryDetails $region
     *
     * @return RegionSubregionJoint
     */
    public function setRegion(\Hr\Entity\DictionaryDetails $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get subregion
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getSubregion()
    {
        return $this->subregion;
    }

    /**
     * Set subregion
     *
     * @param \Hr\Entity\DictionaryDetails $subregion
     *
     * @return RegionSubregionJoint
     */
    public function setSubregion(\Hr\Entity\DictionaryDetails $subregion = null)
    {
        $this->subregion = $subregion;

        return $this;
    }
}

