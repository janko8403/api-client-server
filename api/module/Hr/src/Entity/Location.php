<?php

namespace Hr\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Location
 */
class Location
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var null | float
     */
    private $lat = null;

    /**
     * @var null | float
     */
    private $lng = null;

    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $range;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float|null $lat
     * @return Location
     */
    public function setLat(?float $lat): Location
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param float|null $lng
     * @return Location
     */
    public function setLng(?float $lng): Location
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Location
     */
    public function setType(int $type): Location
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getRange(): int
    {
        return $this->range;
    }

    /**
     * @param int $range
     * @return Location
     */
    public function setRange(int $range): Location
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $messages
     * @return Location
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }


}
