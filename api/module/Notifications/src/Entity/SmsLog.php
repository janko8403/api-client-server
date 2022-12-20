<?php

namespace Notifications\Entity;

/**
 * SmsLog
 */
class SmsLog
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $apiId;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string|null
     */
    private $error;

    /**
     * @var \DateTime
     */
    private $creationDate;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
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
     * Get apiId.
     *
     * @return string
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * Set apiId.
     *
     * @param string $apiId
     *
     * @return SmsLog
     */
    public function setApiId($apiId)
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number.
     *
     * @param string $number
     *
     * @return SmsLog
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return SmsLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get error.
     *
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error.
     *
     * @param string|null $error
     *
     * @return SmsLog
     */
    public function setError($error = null)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime $creationDate
     *
     * @return SmsLog
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
