<?php

namespace Hr\Entity;

/**
 * Subchain
 */
class Subchain
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var boolean
     */
    private $isActive = '1';

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $chain;

    /**
     * @var string
     */
    private $variable1;

    /**
     * @var string
     */
    private $variable2;

    /**
     * @var string
     */
    private $variable3;

    /**
     * @var string
     */
    private $variable4;

    /**
     * @var string
     */
    private $variable5;

    /**
     * @var string
     */
    private $variable6;

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
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Subchain
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

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
     * @return Subchain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get chain
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getChain()
    {
        return $this->chain;
    }

    /**
     * Set chain
     *
     * @param \Hr\Entity\DictionaryDetails $chain
     *
     * @return Subchain
     */
    public function setChain(\Hr\Entity\DictionaryDetails $chain = null)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Get variable1
     *
     * @return string
     */
    public function getVariable1()
    {
        return $this->variable1;
    }

    /**
     * Set variable1
     *
     * @param string $variable1
     *
     * @return Subchain
     */
    public function setVariable1($variable1)
    {
        $this->variable1 = $variable1;

        return $this;
    }

    /**
     * Get variable2
     *
     * @return string
     */
    public function getVariable2()
    {
        return $this->variable2;
    }

    /**
     * Set variable2
     *
     * @param string $variable2
     *
     * @return Subchain
     */
    public function setVariable2($variable2)
    {
        $this->variable2 = $variable2;

        return $this;
    }

    /**
     * Get variable3
     *
     * @return string
     */
    public function getVariable3()
    {
        return $this->variable3;
    }

    /**
     * Set variable3
     *
     * @param string $variable3
     *
     * @return Subchain
     */
    public function setVariable3($variable3)
    {
        $this->variable3 = $variable3;

        return $this;
    }

    /**
     * Get variable4
     *
     * @return string
     */
    public function getVariable4()
    {
        return $this->variable4;
    }

    /**
     * Set variable4
     *
     * @param string $variable4
     *
     * @return Subchain
     */
    public function setVariable4($variable4)
    {
        $this->variable4 = $variable4;

        return $this;
    }

    /**
     * Get variable5
     *
     * @return string
     */
    public function getVariable5()
    {
        return $this->variable5;
    }

    /**
     * Set variable5
     *
     * @param string $variable5
     *
     * @return Subchain
     */
    public function setVariable5($variable5)
    {
        $this->variable5 = $variable5;

        return $this;
    }

    /**
     * Get variable6
     *
     * @return string
     */
    public function getVariable6()
    {
        return $this->variable6;
    }

    /**
     * Set variable6
     *
     * @param string $variable6
     *
     * @return Subchain
     */
    public function setVariable6($variable6)
    {
        $this->variable6 = $variable6;

        return $this;
    }
}
