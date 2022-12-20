<?php

namespace Configuration\Entity;

/**
 * ObjectFieldDetail
 */
class ObjectFieldDetail
{
    const SETTING_VIEW = 1;
    const SETTING_SAVE = 2;
    const SETTING_ADD = 3;
    const SETTING_NO = 4;
    const SETTING_SAVE_NOT_EMPTY = 5;
    const SETTING_SAVE_ADD_NOT_EMPTY = 6;

    public static function getSettings()
    {
        return [
            self::SETTING_VIEW => 'PODGLĄD',
            self::SETTING_SAVE => 'ZAPIS',
            self::SETTING_ADD => 'ZAPIS, dodawanie',
            self::SETTING_SAVE_NOT_EMPTY => 'ZAPIS, jeśli puste',
            self::SETTING_SAVE_ADD_NOT_EMPTY => 'ZAPIS + dodawanie, jeśli niepuste',
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
    private $settingSmall = self::SETTING_SAVE;

    /**
     * @var integer
     */
    private $settingMedium = self::SETTING_SAVE;

    /**
     * @var integer
     */
    private $settingLarge = self::SETTING_SAVE;

    /**
     * @var \Configuration\Entity\ObjectField
     */
    private $field;

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
     * @return ObjectFieldDetail
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
     * @return ObjectFieldDetail
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
     * @return ObjectFieldDetail
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
     * Set field
     *
     * @param \Configuration\Entity\ObjectField $field
     *
     * @return ObjectFieldDetail
     */
    public function setField(\Configuration\Entity\ObjectField $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \Configuration\Entity\ObjectField
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set position
     *
     * @param \Configuration\Entity\Position $position
     *
     * @return ObjectFieldDetail
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

    public function setId($id){
        $this->id = $id;
    }
}

