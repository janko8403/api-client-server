<?php

namespace Notifications\Entity;

/**
 * NotificationRecipient
 */
class NotificationRecipient
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $strategy;

    /**
     * @var string
     */
    private $params;

    /**
     * @var \Notifications\Entity\Notification
     */
    private $notification;


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
     * Set strategy
     *
     * @param string $strategy
     *
     * @return NotificationRecipient
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * Get strategy
     *
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Set params
     *
     * @param string $params
     *
     * @return NotificationRecipient
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set notification
     *
     * @param \Notifications\Entity\Notification $notification
     *
     * @return NotificationRecipient
     */
    public function setNotification(\Notifications\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Notifications\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}

