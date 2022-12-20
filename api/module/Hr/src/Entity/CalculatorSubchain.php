<?php

namespace Hr\Entity;

/**
 * CalculatorSubchain
 */
class CalculatorSubchain
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $subchainId;

    /**
     * @var integer
     */
    private $calculatorSubchainId;


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
     * Get subchainId
     *
     * @return integer
     */
    public function getSubchainId()
    {
        return $this->subchainId;
    }

    /**
     * Set subchainId
     *
     * @param integer $subchainId
     *
     * @return CalculatorSubchain
     */
    public function setSubchainId($subchainId)
    {
        $this->subchainId = $subchainId;

        return $this;
    }

    /**
     * Get calculatorSubchainId
     *
     * @return integer
     */
    public function getCalculatorSubchainId()
    {
        return $this->calculatorSubchainId;
    }

    /**
     * Set calculatorSubchainId
     *
     * @param integer $calculatorSubchainId
     *
     * @return CalculatorSubchain
     */
    public function setCalculatorSubchainId($calculatorSubchainId)
    {
        $this->calculatorSubchainId = $calculatorSubchainId;

        return $this;
    }
}

