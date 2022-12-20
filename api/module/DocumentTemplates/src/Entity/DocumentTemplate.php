<?php

namespace DocumentTemplates\Entity;

/**
 * DocumentTemplate
 */
class DocumentTemplate
{
    const EVENT_GENERATE_DOCUMENT = 'eventGenerateDocument';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $contentHeader;

    /**
     * @var string
     */
    private $contentBody;

    /**
     * @var string
     */
    private $contentFooter;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var boolean
     */
    private $isActive = true;

    /**
     * @var integer
     */
    private $type;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }

    public static function getTags()
    {
        return [
            // ...
        ];
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return DocumentTemplate
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get contentHeader
     *
     * @return string
     */
    public function getContentHeader()
    {
        return $this->contentHeader;
    }

    /**
     * Set contentHeader
     *
     * @param string $contentHeader
     *
     * @return DocumentTemplate
     */
    public function setContentHeader($contentHeader)
    {
        $this->contentHeader = $contentHeader;

        return $this;
    }

    /**
     * Get contentBody
     *
     * @return string
     */
    public function getContentBody()
    {
        return $this->contentBody;
    }

    /**
     * Set contentBody
     *
     * @param string $contentBody
     *
     * @return DocumentTemplate
     */
    public function setContentBody($contentBody)
    {
        $this->contentBody = $contentBody;

        return $this;
    }

    /**
     * Get contentFooter
     *
     * @return string
     */
    public function getContentFooter()
    {
        return $this->contentFooter;
    }

    /**
     * Set contentFooter
     *
     * @param string $contentFooter
     *
     * @return DocumentTemplate
     */
    public function setContentFooter($contentFooter)
    {
        $this->contentFooter = $contentFooter;

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
     * @return DocumentTemplate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return DocumentTemplate
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
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

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return DocumentTemplate
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
