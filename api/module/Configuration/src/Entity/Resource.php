<?php

namespace Configuration\Entity;

use Doctrine\Common\Collections\Criteria;

/**
 * Resource
 */
class Resource
{
    const SETTING_NO = 0;
    const SETTING_YES = 1;

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
    private $label;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParams;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var integer
     */
    private $sequence;

    /**
     * @var boolean
     */
    private $isUserMenu;

    /**
     * @var boolean
     */
    private $isCustomerMenu;

    /**
     * @var boolean
     */
    private $isHidden;

    /**
     * @var integer
     */
    private $settingSmall;

    /**
     * @var integer
     */
    private $settingMedium;

    /**
     * @var integer
     */
    private $settingLarge;

    /**
     * @var string
     */
    private $cookie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subresources;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $resourcePositions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objects;

    /**
     * @var \Configuration\Entity\Resource
     */
    private $parent;

    /**
     * @var boolean
     */
    private $isShortcutMenu;

    /**
     * @var bool
     */
    private $isUserDetailsMenu;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subresources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resourcePositions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Resource
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set label
     *
     * @param string $label
     *
     * @return Resource
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return Resource
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeParams
     *
     * @param array $routeParams
     *
     * @return Resource
     */
    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * Get routeParams
     *
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Resource
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return Resource
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set isUserMenu
     *
     * @param boolean $isUserMenu
     *
     * @return Resource
     */
    public function setIsUserMenu($isUserMenu)
    {
        $this->isUserMenu = $isUserMenu;

        return $this;
    }

    /**
     * Get isUserMenu
     *
     * @return boolean
     */
    public function getIsUserMenu()
    {
        return $this->isUserMenu;
    }

    /**
     * Set isCustomerMenu
     *
     * @param boolean $isCustomerMenu
     *
     * @return Resource
     */
    public function setIsCustomerMenu($isCustomerMenu)
    {
        $this->isCustomerMenu = $isCustomerMenu;

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
     * Set isHidden
     *
     * @param boolean $isHidden
     *
     * @return Resource
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * Get isHidden
     *
     * @return boolean
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * Set settingSmall
     *
     * @param integer $settingSmall
     *
     * @return Resource
     */
    public function setSettingSmall($settingSmall)
    {
        $this->settingSmall = $settingSmall;

        return $this;
    }

    /**
     * Get settingSmall
     *
     * @return integer
     */
    public function getSettingSmall()
    {
        return $this->settingSmall;
    }

    /**
     * Set settingMedium
     *
     * @param integer $settingMedium
     *
     * @return Resource
     */
    public function setSettingMedium($settingMedium)
    {
        $this->settingMedium = $settingMedium;

        return $this;
    }

    /**
     * Get settingMedium
     *
     * @return integer
     */
    public function getSettingMedium()
    {
        return $this->settingMedium;
    }

    /**
     * Set settingLarge
     *
     * @param integer $settingLarge
     *
     * @return Resource
     */
    public function setSettingLarge($settingLarge)
    {
        $this->settingLarge = $settingLarge;

        return $this;
    }

    /**
     * Get settingLarge
     *
     * @return integer
     */
    public function getSettingLarge()
    {
        return $this->settingLarge;
    }

    /**
     * Add subresource
     *
     * @param \Configuration\Entity\Resource $subresource
     *
     * @return Resource
     */
    public function addSubresource(\Configuration\Entity\Resource $subresource)
    {
        $this->subresources[] = $subresource;

        return $this;
    }

    /**
     * Remove subresource
     *
     * @param \Configuration\Entity\Resource $subresource
     */
    public function removeSubresource(\Configuration\Entity\Resource $subresource)
    {
        $this->subresources->removeElement($subresource);
    }

    /**
     * Get subresources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubresources()
    {
        return $this->subresources;
    }

    /**
     * Add resourcePosition
     *
     * @param \Configuration\Entity\ResourcePosition $resourcePosition
     *
     * @return Resource
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

    /**
     * Set parent
     *
     * @param \Configuration\Entity\Resource $parent
     *
     * @return Resource
     */
    public function setParent(\Configuration\Entity\Resource $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Configuration\Entity\Resource
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set cookie
     *
     * @param string $cookie
     *
     * @return Resource
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Get cookie
     *
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Add object
     *
     * @param \Configuration\Entity\ObjectGroup $object
     *
     * @return Resource
     */
    public function addObject(\Configuration\Entity\ObjectGroup $object)
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * Remove object
     *
     * @param \Configuration\Entity\ObjectGroup $object
     */
    public function removeObject(\Configuration\Entity\ObjectGroup $object)
    {
        $this->objects->removeElement($object);
    }

    /**
     * Get objects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Set isShortcutMenu
     *
     * @param boolean $isShortcutMenu
     *
     * @return Resource
     */
    public function setIsShortcutMenu($isShortcutMenu)
    {
        $this->isShortcutMenu = $isShortcutMenu;

        return $this;
    }

    /**
     * Get isShortcutMenu
     *
     * @return boolean
     */
    public function getIsShortcutMenu()
    {
        return $this->isShortcutMenu;
    }

    /**
     * @return bool
     */
    public function getIsUserDetailsMenu(): ?bool
    {
        return $this->isUserDetailsMenu;
    }

    /**
     * @param bool $isUserDetailsMenu
     * @return Resource
     */
    public function setIsUserDetailsMenu(bool $isUserDetailsMenu): Resource
    {
        $this->isUserDetailsMenu = $isUserDetailsMenu;

        return $this;
    }

    ////////////////////////////

    public function getUserMenuChildren()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isUserMenu', true))
            ->orderBy(['sequence' => Criteria::ASC]);

        return $this->getSubresources()->matching($criteria);
    }


}
