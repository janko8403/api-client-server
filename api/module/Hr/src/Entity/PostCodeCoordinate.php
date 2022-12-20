<?php

namespace Hr\Entity;

/**
 * PostCodeCoordinate
 */
class PostCodeCoordinate
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
     * @var float|null
     */
    private $lat;

    /**
     * @var float|null
     */
    private $lng;


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
     * @return PostCodeCoordinate
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get lat.
     *
     * @return float|null
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lat.
     *
     * @param float|null $lat
     *
     * @return PostCodeCoordinate
     */
    public function setLat($lat = null)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lng.
     *
     * @return float|null
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lng.
     *
     * @param float|null $lng
     *
     * @return PostCodeCoordinate
     */
    public function setLng($lng = null)
    {
        $this->lng = $lng;

        return $this;
    }
}
