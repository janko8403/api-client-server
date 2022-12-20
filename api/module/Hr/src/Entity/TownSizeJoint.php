<?php

namespace Hr\Entity;

/**
 * TownSizeJoint
 */
class TownSizeJoint
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $town;

    /**
     * @var \Hr\Entity\TownSize
     */
    private $size;


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
     * Get town
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set town
     *
     * @param \Hr\Entity\DictionaryDetails $town
     *
     * @return TownSizeJoint
     */
    public function setTown(\Hr\Entity\DictionaryDetails $town = null)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get size
     *
     * @return \Hr\Entity\TownSize
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size
     *
     * @param \Hr\Entity\TownSize $size
     *
     * @return TownSizeJoint
     */
    public function setSize(\Hr\Entity\TownSize $size = null)
    {
        $this->size = $size;

        return $this;
    }
}

