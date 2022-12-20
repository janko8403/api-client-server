<?php

namespace Hr\Entity;

/**
 * SubregionMacroregionJoint
 */
class SubregionMacroregionJoint
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $macroregion;

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
     * Get macroregion
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getMacroregion()
    {
        return $this->macroregion;
    }

    /**
     * Set macroregion
     *
     * @param \Hr\Entity\DictionaryDetails $macroregion
     *
     * @return SubregionMacroregionJoint
     */
    public function setMacroregion(\Hr\Entity\DictionaryDetails $macroregion = null)
    {
        $this->macroregion = $macroregion;

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
     * @return SubregionMacroregionJoint
     */
    public function setSubregion(\Hr\Entity\DictionaryDetails $subregion = null)
    {
        $this->subregion = $subregion;

        return $this;
    }
}

