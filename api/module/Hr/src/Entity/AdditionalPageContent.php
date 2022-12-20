<?php

namespace Hr\Entity;

/**
 * AdditionalPageContent
 */
class AdditionalPageContent
{
    const TYPE_BOTTOM = 1;
    const TYPE_MAIN_PAGE = 10;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isVisible;

    /**
     * @var boolean
     */
    private $placement;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $order = '1';


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
     * Get isVisible
     *
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return AdditionalPageContent
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get placement
     *
     * @return boolean
     */
    public function getPlacement()
    {
        return $this->placement;
    }

    /**
     * Set placement
     *
     * @param boolean $placement
     *
     * @return AdditionalPageContent
     */
    public function setPlacement($placement)
    {
        $this->placement = $placement;

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
     * @return AdditionalPageContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get order
     *
     * @return boolean
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return AdditionalPageContent
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}

