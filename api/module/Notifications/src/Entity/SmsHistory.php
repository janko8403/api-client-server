<?php

namespace Notifications\Entity;

/**
 * SmsHistory
 */
class SmsHistory
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
     * @var float
     */
    private $number;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $sendDate;

    /**
     * @var \DateTime|null
     */
    private $responseDate;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string|null
     */
    private $responseText;

    /**
     * @var float
     */
    private $parts;

    /**
     * @var array|null
     */
    private $additionalInfo;

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
     * Set apiId.
     *
     * @param string $apiId
     *
     * @return SmsHistory
     */
    public function setApiId($apiId)
    {
        $this->apiId = $apiId;

        return $this;
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
     * Set number.
     *
     * @param string $number
     *
     * @return SmsHistory
     */
    public function setNumber($number)
    {
        $this->number = $number;

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
     * Set name.
     *
     * @param string $name
     *
     * @return SmsHistory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sendDate.
     *
     * @param \DateTime $sendDate
     *
     * @return SmsHistory
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate.
     *
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set responseDate.
     *
     * @param \DateTime|null $responseDate
     *
     * @return SmsHistory
     */
    public function setResponseDate($responseDate = null)
    {
        $this->responseDate = $responseDate;

        return $this;
    }

    /**
     * Get responseDate.
     *
     * @return \DateTime|null
     */
    public function getResponseDate()
    {
        return $this->responseDate;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return SmsHistory
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set responseText.
     *
     * @param string|null $responseText
     *
     * @return SmsHistory
     */
    public function setResponseText($responseText = null)
    {
        $this->responseText = $responseText;

        return $this;
    }

    /**
     * Get responseText.
     *
     * @return string|null
     */
    public function getResponseText()
    {
        return $this->responseText;
    }

    /**
     * Set parts.
     *
     * @param float $parts
     *
     * @return SmsHistory
     */
    public function setParts($parts)
    {
        $this->parts = $parts;

        return $this;
    }

    /**
     * Get parts.
     *
     * @return float
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Set additionalInfo.
     *
     * @param array $additionalInfo
     *
     * @return SmsHistory
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    /**
     * Get additionalInfo.
     *
     * @return array|null
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }
}
