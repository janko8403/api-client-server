<?php

namespace Notifications\Entity;

/**
 * EmailThreshold
 */
class EmailThreshold
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $sentEmails = 0;

    public function __construct()
    {
        $this->date = new \DateTime();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return EmailThreshold
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set sentEmails
     *
     * @param integer $sentEmails
     *
     * @return EmailThreshold
     */
    public function setSentEmails($sentEmails)
    {
        $this->sentEmails = $sentEmails;

        return $this;
    }

    /**
     * Get sentEmails
     *
     * @return integer
     */
    public function getSentEmails()
    {
        return $this->sentEmails;
    }
}

