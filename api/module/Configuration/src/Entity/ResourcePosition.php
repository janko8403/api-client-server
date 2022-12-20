<?php

namespace Configuration\Entity;

/**
 * ResourcePosition
 */
class ResourcePosition
{
    const SETTING_YES = 1;
    const SETTING_NO = 2;

    public static function getSettings()
    {
        return [
            self::SETTING_YES => 'TAK',
            self::SETTING_NO => 'NIE',
        ];
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $settingSmall = self::SETTING_YES;

    /**
     * @var integer
     */
    private $settingMedium = self::SETTING_YES;

    /**
     * @var integer
     */
    private $settingLarge = self::SETTING_YES;

    /**
     * @var \Configuration\Entity\Resource
     */
    private $resource;

    /**
     * @var \Configuration\Entity\Position
     */
    private $position;


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
     * Set settingSmall
     *
     * @param integer $settingSmall
     *
     * @return ResourcePosition
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
     * @return ResourcePosition
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
     * @return ResourcePosition
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
     * Set resource
     *
     * @param \Configuration\Entity\Resource $resource
     *
     * @return ResourcePosition
     */
    public function setResource(\Configuration\Entity\Resource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return \Configuration\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set position
     *
     * @param \Configuration\Entity\Position $position
     *
     * @return ResourcePosition
     */
    public function setPosition(\Configuration\Entity\Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Configuration\Entity\Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    ////////////////////////////////////
    public function setId($id){
        $this->id = $id;
    }
}

