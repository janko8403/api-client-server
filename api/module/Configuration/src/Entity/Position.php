<?php

namespace Configuration\Entity;

use Hr\Entity\DictionaryDetailBase;

/**
 * Position
 */
class Position extends DictionaryDetailBase
{
    const POSITION_ADMIN = 'admin';
    const POSITION_TIKROW_PARTNER = 'tikrowPartner';
    const POSITION_TIKROW_MANAGER = 'tikrowManager';
    const POSITION_TIKROW_REGIONAL_MANAGER = 'tikrowRegionalManager';
    const POSITION_TIKROW_NATIONAL_MANAGER = 'tikrowNationalManager';
    const POSITION_CRM_CUSTOMER = 'crmCentralaKlient';

    const ID_CUSTOMER_SERVICE = 6;
    const ID_ADMINISTRATOR_TYPE_1 = 2;
    const ID_ADMINISTRATOR_TYPE_2 = 1;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $resourcePositions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resourcePositions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add resourcePosition
     *
     * @param \Configuration\Entity\ResourcePosition $resourcePosition
     *
     * @return Position
     */
    public function addResourcePosition(\Configuration\Entity\ResourcePosition $resourcePosition)
    {
        $this->resourcePositions[] = $resourcePosition;

        return $this;
    }

    /**
     * Remove resourcePosition
     *
     * @param \Configuration\Entity\ResourcePosition $resourcePosition
     */
    public function removeResourcePosition(\Configuration\Entity\ResourcePosition $resourcePosition)
    {
        $this->resourcePositions->removeElement($resourcePosition);
    }

    /**
     * Get resourcePositions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResourcePositions()
    {
        return $this->resourcePositions;
    }
}