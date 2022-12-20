<?php

namespace Hr\Entity;

/**
 * RecordPickerFilter
 */
class RecordPickerFilter
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var string
     */
    private $filterId;

    /**
     * @var array
     */
    private $records;

    /**
     * @var array
     */
    private $previousRecords;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \Users\Entity\User
     */
    private $user;


    public function __construct()
    {
        $this->creationDate = new \DateTime();
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

    /**
     * Get filterId
     *
     * @return string
     */
    public function getFilterId()
    {
        return $this->filterId;
    }

    /**
     * Set filterId
     *
     * @param string $filterId
     *
     * @return RecordPickerFilter
     */
    public function setFilterId($filterId)
    {
        $this->filterId = $filterId;

        return $this;
    }

    /**
     * Get records
     *
     * @return array
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Set records
     *
     * @param array $records
     *
     * @return RecordPickerFilter
     */
    public function setRecords($records)
    {
        $this->records = $records;

        return $this;
    }

    /**
     * @return array
     */
    public function getPreviousRecords()
    {
        return $this->previousRecords;
    }

    /**
     * @param array $previousRecords
     * @return RecordPickerFilter
     */
    public function setPreviousRecords($previousRecords): RecordPickerFilter
    {
        $this->previousRecords = $previousRecords;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return RecordPickerFilter
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Users\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Users\Entity\User $user
     *
     * @return RecordPickerFilter
     */
    public function setUser(\Users\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}

