<?php

namespace Notifications\Entity;

/**
 * EmailMessageAddressJoint
 */
class EmailMessageAddressJoint
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
     * @var \Notifications\Entity\EmailMessage
     */
    private $emailMessage;

    /**
     * @var \Notifications\Entity\EmailAddress
     */
    private $emailAddress;


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
     * @return EmailMessageAddressJoint
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
     * Set emailMessage
     *
     * @param \Notifications\Entity\EmailMessage $emailMessage
     *
     * @return EmailMessageAddressJoint
     */
    public function setEmailMessage(\Notifications\Entity\EmailMessage $emailMessage = null)
    {
        $this->emailMessage = $emailMessage;

        return $this;
    }

    /**
     * Get emailMessage
     *
     * @return \Notifications\Entity\EmailMessage
     */
    public function getEmailMessage()
    {
        return $this->emailMessage;
    }

    /**
     * Set emailAddress
     *
     * @param \Notifications\Entity\EmailAddress $emailAddress
     *
     * @return EmailMessageAddressJoint
     */
    public function setEmailAddress(\Notifications\Entity\EmailAddress $emailAddress = null)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return \Notifications\Entity\EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
}

