<?php

namespace Hr\Entity;

/**
 * MemoryModule
 */
class MemoryModule
{
    /**
     * Monitoring - początek dnia
     */
    const KEY_START_DAY = 'startDay';

    /**
     * Monitoring - koniec dnia
     */
    const KEY_END_DAY = 'endDay';

    /**
     * Zamawiacz
     */
    const KEY_ORDER = 'order';

    /**
     * Targety
     */
    const KEY_TARGETS = 'targets';

    /**
     * Lista szablonów tras
     */
    const KEY_ROUTES_TEMPLATES = 'routesTemplates';

    /**
     * Historia Raportów
     */
    const KEY_HISTORY_REPORT = 'raportHistory';

    /**
     * Historia Wizyt
     */
    const KEY_HISTORY_VISIT = 'visitHistory';

    /**
     * Historia Raportów
     */
    const KEY_HISTORY_COOPERATION = 'cooperationHistory';

    /**
     * Historia zamowień
     */
    const KEY_HISTORY_ORDER = 'orderHistory';

    /**
     * Złóż Zamówienie
     */
    const KEY_PREORDER = 'addPreorder';

    /**
     * Trasy
     */
    const KEY_ROUTES = 'routes';

    /**
     * Poczekalnia
     */
    const KEY_LOBBY = 'lobby';

    /**
     * Wyślij komunikat
     */
    const KEY_MESSAGES_SEND = 'sendMessage';

    /**
     * historia złożonych zamówień(złoż zamowienie)
     */
    const KEY_REPORTED_PREORDER_HISTORY = 'reportedPreorderHistory';

    /**
     * Stany magazynowe
     */
    const KEY_WAREHOUSE = 'warehouse';

    /**
     * moduł elerningu
     */
    const KEY_ELERNING = 'elearning';

    const TYPE_ELEARNING = 32;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isMonitoring;

    /**
     * @var boolean
     */
    private $inFlow = '1';

    /**
     * @var string
     */
    private $key;

    /**
     * @var boolean
     */
    private $isCustomerMenu = '0';

    /**
     * @var boolean
     */
    private $isSubcustomerMenu = '0';

    /**
     * @var boolean
     */
    private $isMainMenu = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $details;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->details = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get isMonitoring
     *
     * @return boolean
     */
    public function getIsMonitoring()
    {
        return $this->isMonitoring;
    }

    /**
     * Set isMonitoring
     *
     * @param boolean $isMonitoring
     *
     * @return MemoryModule
     */
    public function setIsMonitoring($isMonitoring)
    {
        $this->isMonitoring = $isMonitoring;

        return $this;
    }

    /**
     * Get inFlow
     *
     * @return boolean
     */
    public function getInFlow()
    {
        return $this->inFlow;
    }

    /**
     * Set inFlow
     *
     * @param boolean $inFlow
     *
     * @return MemoryModule
     */
    public function setInFlow($inFlow)
    {
        $this->inFlow = $inFlow;

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
     * @return MemoryModule
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get isCustomerMenu
     *
     * @return boolean
     */
    public function getIsCustomerMenu()
    {
        return $this->isCustomerMenu;
    }

    /**
     * Set isCustomerMenu
     *
     * @param boolean $isCustomerMenu
     *
     * @return MemoryModule
     */
    public function setIsCustomerMenu($isCustomerMenu)
    {
        $this->isCustomerMenu = $isCustomerMenu;

        return $this;
    }

    /**
     * Get isSubcustomerMenu
     *
     * @return boolean
     */
    public function getIsSubcustomerMenu()
    {
        return $this->isSubcustomerMenu;
    }

    /**
     * Set isSubcustomerMenu
     *
     * @param boolean $isSubcustomerMenu
     *
     * @return MemoryModule
     */
    public function setIsSubcustomerMenu($isSubcustomerMenu)
    {
        $this->isSubcustomerMenu = $isSubcustomerMenu;

        return $this;
    }

    /**
     * Get isMainMenu
     *
     * @return boolean
     */
    public function getIsMainMenu()
    {
        return $this->isMainMenu;
    }

    /**
     * Set isMainMenu
     *
     * @param boolean $isMainMenu
     *
     * @return MemoryModule
     */
    public function setIsMainMenu($isMainMenu)
    {
        $this->isMainMenu = $isMainMenu;

        return $this;
    }

    /**
     * Add detail
     *
     * @param \Hr\Entity\ModulesDetail $detail
     *
     * @return MemoryModule
     */
    public function addDetail(\Hr\Entity\ModulesDetail $detail)
    {
        $this->details[] = $detail;

        return $this;
    }

    /**
     * Remove detail
     *
     * @param \Hr\Entity\ModulesDetail $detail
     */
    public function removeDetail(\Hr\Entity\ModulesDetail $detail)
    {
        $this->details->removeElement($detail);
    }

    /**
     * Get details
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetails()
    {
        return $this->details;
    }
}
