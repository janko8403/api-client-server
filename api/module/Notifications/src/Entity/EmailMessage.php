<?php

namespace Notifications\Entity;

/**
 * EmailMessage
 */
class EmailMessage
{
    const TYPE_FULFILMENT_REJECTED = 'fulfilmentRejected';
    const TYPE_MONITORING_SUMMARY = 'sendingAnswersMonitorings';
    const TYPE_CRON_WAREHOUSE_STATE_SYNC = 'cronWarehouseStateSynch';

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
    private $key;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $emailAddressJoints;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emailAddressJoints = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set mandantId
     *
     * @param integer $mandantId
     *
     * @return EmailMessage
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
     * Set key
     *
     * @param string $key
     *
     * @return EmailMessage
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
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
     * Set description
     *
     * @param string $description
     *
     * @return EmailMessage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add emailAddressJoint
     *
     * @param \Notifications\Entity\EmailMessageAddressJoint $emailAddressJoint
     *
     * @return EmailMessage
     */
    public function addEmailAddressJoint(\Notifications\Entity\EmailMessageAddressJoint $emailAddressJoint)
    {
        $this->emailAddressJoints[] = $emailAddressJoint;

        return $this;
    }

    /**
     * Remove emailAddressJoint
     *
     * @param \Notifications\Entity\EmailMessageAddressJoint $emailAddressJoint
     */
    public function removeEmailAddressJoint(\Notifications\Entity\EmailMessageAddressJoint $emailAddressJoint)
    {
        $this->emailAddressJoints->removeElement($emailAddressJoint);
    }

    /**
     * Get emailAddressJoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailAddressJoints()
    {
        return $this->emailAddressJoints;
    }
}
