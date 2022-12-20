<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 14.06.2017
 * Time: 16:29
 */

namespace Hr\Setting;

use Doctrine\Persistence\ObjectManager;
use Hr\Entity\SystemSetting;

class SystemSettingsService
{
    const MINIMAL_MARGIN = 'minimalMargin';
    const COMMISSION_SPLIT_LAT = 'commissionSplitLat';
    const COMMISSION_SPLIT_LNG = 'commissionSplitLng';
    const COMMISSION_SPLIT_THRESHOLD = 'commissionSplitThreshold';

    private ObjectManager $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Gets system setting with given name.
     *
     * @param string $name
     * @return array|mixed
     * @throws \Exception
     */
    public function get(string $name)
    {
        $setting = $this->objectManager->find(SystemSetting::class, $name);

        if (!$setting) {
            throw new \Exception("Cannot find setting `$name`");
        }

        if ($setting->getSelectTable() || $setting->getSelectQuery()) {
            return $setting->getValue() ? unserialize($setting->getValue()) : [];
        }

        return $setting->getValue();
    }

    /**
     * Sets system setting.
     *
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value): void
    {
        $setting = $this->objectManager->find(SystemSetting::class, $name);

        if (!$setting) {
            $setting = new  SystemSetting();
            $setting->setName($name);
            $setting->setLabel($name);
        }

        $setting->setValue($value);
        $this->objectManager->persist($setting);
        $this->objectManager->flush();
    }
}