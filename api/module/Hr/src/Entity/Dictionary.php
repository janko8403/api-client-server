<?php

namespace Hr\Entity;

/**
 * Dictionary
 */
class Dictionary
{
    const DIC_CHAINS = 1;
    const DIC_REGIONS = 2;
    const DIC_FORMATS = 3;
    const DIC_SIZES = 4;
    const DIC_CITIES = 5;
    const DIC_MACROREGIONS = 6;
    const DIC_SUBREGIONS = 7;
    const DIC_TARGETS = 8;
    const DIC_CUSTOMER_GROUPS = 9;
    const DIC_SALE_STAGES = 10;
    const DIC_CUSTOMER_SEEDING_STRUCTURES = 11;
    const DIC_ROUTEH_EADLINE = 12;
    const DIC_PROMOTER_EXPOSURE_MANNERS = 13;
    const DIC_VISIT_REASONS = 14;
    const DIC_MONITORING_CONTACT_TYPES = 15;
    const DIC_CUSTOMER_STATUS = 16;
    const DIC_PAYMENT_PERIOD = 17;
    const DIC_PROMOTER_TYPES = 18;
    const DIC_PRODUCT_KINDS = 19;
    const DIC_ROUTE_REASONS = 20;
    const DIC_CUSTOMER_PRIORITIES = 21;
    const DIC_VISITS_FREQUENCY = 22;
    const DIC_CALENDAR_EVENT_TYPES = 23;
    const DIC_PROMOTER_EXPOSURE_SIZE = 24;
    const DIC_STREET_PREFIXES = 25;
    const DIC_PRODUCTSUPERGROUPS = 26;
    const DIC_MONITORING_GROUPS = 27;
    const DIC_PROMOTER_ACTIVITY_TYPES = 28;
    const DIC_ORDER_WHOLESALER_DISPLAY = 29;
    const DIC_CAMPAIGNS = 30;
    const DIC_POSITIONS = 31;
    const DIC_KNOWLEDGECATEGORIES = 32;
    const DIC_TARGETS_SUMS = 33;
    const DIC_CONTACT_IN_DAYS = 34;
    const DIC_SUBFORMATS = 36;
    const DIC_SUBSIZES = 35;

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
    private $key;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Hr\Entity\Dictionary
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Dictionary
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set key
     *
     * @param string $key
     *
     * @return Dictionary
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Add child
     *
     * @param \Hr\Entity\Dictionary $child
     *
     * @return Dictionary
     */
    public function addChild(\Hr\Entity\Dictionary $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Hr\Entity\Dictionary $child
     */
    public function removeChild(\Hr\Entity\Dictionary $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get parent
     *
     * @return \Hr\Entity\Dictionary
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Hr\Entity\Dictionary $parent
     *
     * @return Dictionary
     */
    public function setParent(\Hr\Entity\Dictionary $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }
}
