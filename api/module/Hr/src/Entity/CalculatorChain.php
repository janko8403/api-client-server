<?php

namespace Hr\Entity;

/**
 * CalculatorChain
 */
class CalculatorChain
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $chainId;

    /**
     * @var integer
     */
    private $calculatorChainId;


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
     * Get chainId
     *
     * @return integer
     */
    public function getChainId()
    {
        return $this->chainId;
    }

    /**
     * Set chainId
     *
     * @param integer $chainId
     *
     * @return CalculatorChain
     */
    public function setChainId($chainId)
    {
        $this->chainId = $chainId;

        return $this;
    }

    /**
     * Get calculatorChainId
     *
     * @return integer
     */
    public function getCalculatorChainId()
    {
        return $this->calculatorChainId;
    }

    /**
     * Set calculatorChainId
     *
     * @param integer $calculatorChainId
     *
     * @return CalculatorChain
     */
    public function setCalculatorChainId($calculatorChainId)
    {
        $this->calculatorChainId = $calculatorChainId;

        return $this;
    }
}

