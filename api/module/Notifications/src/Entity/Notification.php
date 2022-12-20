<?php

namespace Notifications\Entity;

/**
 * Notification
 */
class Notification
{
    const EVENT_SEND_NOTIFICATION = 'eventSendNotification';

    const TRANSPORT_EMAIL = 1;
    const TRANSPORT_SMS = 2;

    const TYPE_EMAIL_RESET_PASSWORD = 1;
    const TYPE_SMS_ORDER_ACCEPTED = 1000;

    const TYPES = [
        self::TYPE_EMAIL_RESET_PASSWORD => 'Email - Resetowanie hasła',
        self::TYPE_SMS_ORDER_ACCEPTED => 'SMS - Potwierdzenie akceptacji zlecenia',
    ];

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var integer
     */
    private $transport;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recipients;

    /**
     * @var array|null
     */
    private $params;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recipients = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Notification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Add recipient
     *
     * @param \Notifications\Entity\NotificationRecipient $recipient
     *
     * @return Notification
     */
    public function addRecipient(\Notifications\Entity\NotificationRecipient $recipient)
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    /**
     * Remove recipient
     *
     * @param \Notifications\Entity\NotificationRecipient $recipient
     */
    public function removeRecipient(\Notifications\Entity\NotificationRecipient $recipient)
    {
        $this->recipients->removeElement($recipient);
    }

    /**
     * Get recipients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Get transport
     *
     * @return integer
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set transport
     *
     * @param integer $transport
     *
     * @return Notification
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @param array|null $params
     * @return Notification
     */
    public function setParams(?array $params): Notification
    {
        $this->params = $params;

        return $this;
    }

    public function getTypeName(): string
    {
        return self::TYPES[$this->getType()] ?? '-';
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    ////////////////////

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
