<?php

namespace Customers\Entity;

/**
 * CustomerData
 */
class CustomerData
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var boolean
     */
    private $isActive = 1;

    /**
     * @var \DateTime
     */
    private $modificationDate;

    /**
     * @var string
     */
    private $nip;

    /**
     * @var string
     */
    private $regon;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $name2;

    /**
     * @var string
     */
    private $streetPrefix;

    /**
     * @var string
     */
    private $streetName;

    /**
     * @var string
     */
    private $streetNumber;

    /**
     * @var string
     */
    private $localNumber;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $city;

    /**
     * @var \Customers\Entity\Customer
     */
    private $customer;

    public function __construct()
    {
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

    public function setId($id)
    {
        $this->id = $id;

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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return CustomerData
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get modificationDate
     *
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     *
     * @return CustomerData
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * Get nip
     *
     * @return string
     */
    public function getNip()
    {
        return $this->nip;
    }

    /**
     * Set nip
     *
     * @param string $nip
     *
     * @return CustomerData
     */
    public function setNip($nip)
    {
        $this->nip = $nip;

        return $this;
    }

    /**
     * Get regon
     *
     * @return string
     */
    public function getRegon()
    {
        return $this->regon;
    }

    /**
     * Set regon
     *
     * @param string $regon
     *
     * @return CustomerData
     */
    public function setRegon($regon)
    {
        $this->regon = $regon;

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
     * @return CustomerData
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get streetPrefix
     *
     * @return string
     */
    public function getStreetPrefix()
    {
        return $this->streetPrefix;
    }

    /**
     * Set streetPrefix
     *
     * @param string $streetPrefix
     *
     * @return CustomerData
     */
    public function setStreetPrefix($streetPrefix)
    {
        $this->streetPrefix = $streetPrefix;

        return $this;
    }

    /**
     * Get streetName
     *
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Set streetName
     *
     * @param string $streetName
     *
     * @return CustomerData
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return CustomerData
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get localNumber
     *
     * @return string
     */
    public function getLocalNumber()
    {
        return $this->localNumber;
    }

    /**
     * Set localNumber
     *
     * @param string $localNumber
     *
     * @return CustomerData
     */
    public function setLocalNumber($localNumber)
    {
        $this->localNumber = $localNumber;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return CustomerData
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param \Hr\Entity\DictionaryDetails $city
     *
     * @return CustomerData
     */
    public function setCity(\Hr\Entity\DictionaryDetails $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Customers\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set customer
     *
     * @param \Customers\Entity\Customer $customer
     *
     * @return CustomerData
     */
    public function setCustomer(\Customers\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName2(): ?string
    {
        return $this->name2;
    }

    //////////////////////////

    /**
     * @param string $name2
     * @return CustomerData
     */
    public function setName2(string $name2): CustomerData
    {
        $this->name2 = $name2;

        return $this;
    }

    public function getFullStreetName()
    {
        return (($this->getStreetPrefix()) ? $this->getStreetPrefix() . ' ' : '') .
            $this->getStreetName() . ' ' . $this->getStreetNumber() .
            ($this->getLocalNumber() ? ' / ' . $this->getLocalNumber() : '');
    }

    public function getAddress()
    {
        return $this->getStreetName() . ' ' . $this->getStreetNumber() .
            ($this->getLocalNumber() ? ' / ' . $this->getLocalNumber() : '');
    }

    public function getFullAddress()
    {
        return $this->getZipCode() . ' ' . ($this->getCity() ? $this->getCity()->getName() : '') . ', ' . $this->getFullStreetName();
    }

    public function getZipCity()
    {
        return $this->getZipCode() . ' ' . ($this->getCity() ? $this->getCity()->getName() : '');
    }

    public function getGoogleAddress()
    {
        return sprintf(
            '%s %s, %s',
            $this->getStreetName(),
            $this->getStreetNumber(),
            $this->getZipCity()
        );
    }
}
