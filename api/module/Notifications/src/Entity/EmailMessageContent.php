<?php

namespace Notifications\Entity;

/**
 * EmailMessageContent
 */
class EmailMessageContent
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
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $legend;

    /**
     * @var \Notifications\Entity\EmailMessage
     */
    private $message;


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
     * @return EmailMessageContent
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
     * Set subject
     *
     * @param string $subject
     *
     * @return EmailMessageContent
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
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
     * Set content
     *
     * @param string $content
     *
     * @return EmailMessageContent
     */
    public function setContent($content)
    {
        $this->content = $content;

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
     * Set legend
     *
     * @param string $legend
     *
     * @return EmailMessageContent
     */
    public function setLegend($legend)
    {
        $this->legend = $legend;

        return $this;
    }

    /**
     * Get legend
     *
     * @return string
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * Set message
     *
     * @param \Notifications\Entity\EmailMessage $message
     *
     * @return EmailMessageContent
     */
    public function setMessage(\Notifications\Entity\EmailMessage $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \Notifications\Entity\EmailMessage
     */
    public function getMessage()
    {
        return $this->message;
    }
}

