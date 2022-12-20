<?php

namespace Hr\Entity;

/**
 * DictionaryDetailsDescription
 */
class DictionaryDetailsDescription
{

    const KEY_VARIABLE_1 = 'variable1';
    const KEY_VARIABLE_2 = 'variable2';
    const KEY_VARIABLE_3 = 'variable3';
    const KEY_VARIABLE_4 = 'variable4';
    const KEY_VARIABLE_5 = 'variable5';
    const KEY_VARIABLE_6 = 'variable6';


    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $dictionaryDetail;


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
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return DictionaryDetailsDescription
     */
    public function setKey($key)
    {
        $this->key = $key;

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
     * @return DictionaryDetailsDescription
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get dictionaryDetail
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getDictionaryDetail()
    {
        return $this->dictionaryDetail;
    }

    /**
     * Set dictionaryDetail
     *
     * @param \Hr\Entity\DictionaryDetails $dictionaryDetail
     *
     * @return DictionaryDetailsDescription
     */
    public function setDictionaryDetail(\Hr\Entity\DictionaryDetails $dictionaryDetail = null)
    {
        $this->dictionaryDetail = $dictionaryDetail;

        return $this;
    }
}

