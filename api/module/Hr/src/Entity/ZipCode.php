<?php

namespace Hr\Entity;

/**
 * ZipCode
 */
class ZipCode
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string|null
     */
    private $street;

    /**
     * @var string|null
     */
    private $streetNumbers;

    /**
     * @var string
     */
    private $community;

    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $province;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return ZipCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return ZipCode
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string|null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street.
     *
     * @param string|null $street
     *
     * @return ZipCode
     */
    public function setStreet($street = null)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get streetNumbers.
     *
     * @return string|null
     */
    public function getStreetNumbers()
    {
        return $this->streetNumbers;
    }

    /**
     * Set streetNumbers.
     *
     * @param string|null $streetNumbers
     *
     * @return ZipCode
     */
    public function setStreetNumbers($streetNumbers = null)
    {
        $this->streetNumbers = $streetNumbers;

        return $this;
    }

    /**
     * Get community.
     *
     * @return string
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * Set community.
     *
     * @param string $community
     *
     * @return ZipCode
     */
    public function setCommunity($community)
    {
        $this->community = $community;

        return $this;
    }

    /**
     * Get county.
     *
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Set county.
     *
     * @param string $county
     *
     * @return ZipCode
     */
    public function setCounty($county)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get province.
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set province.
     *
     * @param string $province
     *
     * @return ZipCode
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }
}
