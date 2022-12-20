<?php

namespace Notifications\Entity;

/**
 * EmailAddress
 */
class EmailAddress
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $mandantId;

    /**
     * @var string
     */
    private $address;


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
     * Set mandantId
     *
     * @param integer $mandantId
     *
     * @return EmailAddress
     */
    public function setMandantId($mandantId)
    {
        $this->mandantId = $mandantId;

        return $this;
    }

    /**
     * Get mandantId
     *
     * @return integer
     */
    public function getMandantId()
    {
        return $this->mandantId;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return EmailAddress
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}

