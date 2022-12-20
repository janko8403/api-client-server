<?php

namespace Hr\Entity;

/**
 * CalculatorTownSize
 */
class CalculatorTownSize
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $townSizeId;

    /**
     * @var integer
     */
    private $calculatorTownSizeId;


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
     * Get townSizeId
     *
     * @return integer
     */
    public function getTownSizeId()
    {
        return $this->townSizeId;
    }

    /**
     * Set townSizeId
     *
     * @param integer $townSizeId
     *
     * @return CalculatorTownSize
     */
    public function setTownSizeId($townSizeId)
    {
        $this->townSizeId = $townSizeId;

        return $this;
    }

    /**
     * Get calculatorTownSizeId
     *
     * @return integer
     */
    public function getCalculatorTownSizeId()
    {
        return $this->calculatorTownSizeId;
    }

    /**
     * Set calculatorTownSizeId
     *
     * @param integer $calculatorTownSizeId
     *
     * @return CalculatorTownSize
     */
    public function setCalculatorTownSizeId($calculatorTownSizeId)
    {
        $this->calculatorTownSizeId = $calculatorTownSizeId;

        return $this;
    }
}

