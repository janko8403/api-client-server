<?php

/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.07.2017
 * Time: 12:20
 */

namespace Settings\Service;

use Configuration\Entity\ObjectFieldDetail;
use Configuration\Entity\Position;
use Configuration\Entity\ResourcePosition;
use Doctrine\Persistence\ObjectManager;
use Settings\Entity\PositionVisibility;
use Users\Entity\User;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\EventManager\EventManager;

class PositionVisibilityService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * PositionVisibilityService constructor.
     * @param ObjectManager $objectManager
     * @param EventManager $eventManager
     * @param AbstractAdapter $cacheAdapter
     */
    public function __construct(
        ObjectManager $objectManager,
        EventManager $eventManager,
        AbstractAdapter $cacheAdapter
    ) {
        $this->objectManager = $objectManager;
        $this->eventManager = $eventManager;
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * Saves position visibility data.
     *
     * @param array $data
     */
    public function save(array $data)
    {
        foreach ($data as $positionId => $settings) {
            $visibility = $this->objectManager->getRepository(PositionVisibility::class)->findOneBy(['position' => $positionId]);

            if (!$visibility) {
                $position = $this->objectManager->find(Position::class, $positionId);
                $visibility = new PositionVisibility();
                $visibility->setPosition($position);
            }

            $visibility->setCustomers($settings['customers']);
            $visibility->setCustomersEdit($settings['customersEdit']);
            $visibility->setCustomersPicker($settings['customersPicker']);
            $visibility->setUsers($settings['users']);
            $visibility->setOrders($settings['orders']);
            $visibility->setMonitorings($settings['monitorings']);
            $visibility->setCommissions($settings['commissions']);
            $visibility->setMonitoringsFulfill($settings['monitoringsFulfill']);
            $this->objectManager->persist($visibility);
        }

        $this->objectManager->flush();
    }

    /**
     * Gets visibility data.
     *
     * @return array
     */
    public function getAll()
    {
        $visibility = [];
        $data = $this->objectManager->getRepository(PositionVisibility::class)->findAll();

        foreach ($data as $d) {
            $visibility[$d->getPosition()->getId()] = [
                'customers' => $d->getCustomers(),
                'customersEdit' => $d->getCustomersEdit(),
                'customersPicker' => $d->getCustomersPicker(),
                'users' => $d->getUsers(),
                'orders' => $d->getOrders(),
                'monitorings' => $d->getMonitorings(),
                'commissions' => $d->getCommissions(),
                'monitoringsFulfill' => $d->getMonitoringsFulfill(),
            ];
        }

        return $visibility;
    }

    /** Gets list of regions for a position (using visibility settings).
     *
     * @param string $field
     * @param int $positionId
     * @param int $regionId
     * @param int|null $supervisorId
     * @param int|null $userId
     * @return array|int|null
     * @throws \Exception
     */
    public function getListOfRegions(
        string $field,
        int $positionId,
        int $regionId = null,
        int $supervisorId = null,
        int $userId = null
    ) {
        $positionVisibility = $this->objectManager->getRepository(PositionVisibility::class)
            ->findOneBy(['position' => $positionId]);
        if (empty($regionId)) {
            $regionId = 0;
        }

        $setting = $positionVisibility->get($field);
        switch ($setting) {
            case PositionVisibility::TYPE_ALL:
                return null;
            case PositionVisibility::TYPE_MACROREGION:
                return $this->getRegionsFromCache('macroregion', $regionId);
            case PositionVisibility::TYPE_REGION:
                return (array) $regionId;
            case PositionVisibility::TYPE_SUBREGION:
                return $this->getRegionsFromCache('subregion', $regionId);
            case PositionVisibility::TYPE_SUPERIOR:
                if (empty($supervisorId)) {
                    return [];
                }

                return (array) $this->objectManager->find(User::class, $supervisorId)->getRegion()->getId();
            case PositionVisibility::TYPE_SUBORDINATES:
                $key = CacheService::CACHE_REGIONS_SUBORDINATES . "_$userId";
                $cache = $this->cacheAdapter->getItem($key);

                if (!$cache) {
                    // find subordinates
                    $subordinates = $this->objectManager->getRepository(User::class)->findBy(['supervisor' => $userId]);

                    $regions = [];
                    foreach ($subordinates as $subordinate) {
                        if ($subordinate->getRegion()) {
                            $tmpRegs = (array) $this->getListOfRegions(
                                $field,
                                $subordinate->getConfigurationPosition()->getId(),
                                $subordinate->getRegion()->getId(),
                                null,
                                $subordinate->getId()
                            );
                            $regions = array_merge($regions, $tmpRegs);
                        }
                    }

                    $regions = array_unique($regions);
                    $this->cacheAdapter->setItem($key, $regions);

                    return $regions;
                }

                return $cache;
            default:
                return [];
//                throw new \Exception("Unknown visibility setting: `$setting`");
        }
    }

    /**
     * Checks if customer is available (based on region).
     *
     * @param int $positionId
     * @param int $regionId
     * @param int $customerRegionId
     * @param int|null $supervisorId
     * @return bool
     * @throws \Exception
     */
    public function checkCustomerRegionIsAvailable(
        int $positionId,
        int $regionId = null,
        int $customerRegionId = null,
        int $supervisorId = null
    ) {
        $regionId = $regionId ?? 0;
        $customerRegionId = $customerRegionId ?? 0;

        $regions = $this->getListOfRegions(PositionVisibility::FIELD_CUSTOMERS, $positionId, $regionId, $supervisorId);

        return is_null($regions) || (is_array($regions) && in_array($customerRegionId, $regions));
    }

    /**
     * Check wheather given position in region can fulfill monitorings.
     *
     * @param int $positionId
     * @param int $regionId
     * @param int $customerRegionId
     * @param null $userId
     * @return bool
     * @throws \Exception
     */
    public function checkUserCanFulfillMonitorings(
        int $positionId,
        int $regionId,
        int $customerRegionId,
        $userId = null
    ) {
        $regions = $this->getListOfRegions(
            PositionVisibility::FIELD_MONITORINGS_FULFILL,
            $positionId,
            $regionId,
            null,
            $userId
        );

        return is_null($regions) || (is_array($regions) && in_array($customerRegionId, $regions));
    }

    public function checkUserCanEditCustomer(
        int $positionId,
        int $regionId,
        int $customerRegionId
    ) {
        $regions = $this->getListOfRegions(PositionVisibility::FIELD_CUSTOMERS_EDIT, $positionId, $regionId);

        return is_null($regions) || (is_array($regions) && in_array($customerRegionId, $regions));
    }

    /**
     * Gets cached list of regions in subregion/macroregion
     *
     * @param string $type
     * @param int $region
     * @return array
     */
    public function getRegionsFromCache(string $type, int $region)
    {
        if (!$this->cacheAdapter->hasItem(CacheService::CACHE_REGIONS)) {
            $this->eventManager->trigger(CacheService::EVENT_BUILD_POSITION_VISIBILITY);
        }
        $cache = $this->cacheAdapter->getItem(CacheService::CACHE_REGIONS);

        if (isset($cache[$region])) {
            return $cache[$region][$type];
        } else {
            return [];
        }
    }

    /**
     * Copy privileges beatwean two Positions
     * @param $data
     * @throws \Exception
     */
    public function copyPrivileges($data){
        // $data['positionFrom'];
        // $data['positionTo']
        $positionTo = $this->objectManager->getRepository(Position::class)
            ->find($data['positionTo']);
        //resources to copy
        $resourcePositionFrom = $this->objectManager->getRepository(ResourcePosition::class)
            ->findBy(['position' => $data['positionFrom']]);
        // to delete
        $resourcePositionTo = $this->objectManager->getRepository(ResourcePosition::class)
            ->findBy(['position' => $data['positionTo']]);

        //objectFieldDetails to copy
        $objectFieldDetailsFrom = $this->objectManager->getRepository(ObjectFieldDetail::class)
            ->findBy(['position' => $data['positionFrom']]);

        //objectFieldDetails to delete
        $objectFieldDetailsTo = $this->objectManager->getRepository(ObjectFieldDetail::class)
            ->findBy(['position' => $data['positionTo']]);
        if (!$objectFieldDetailsFrom || !$resourcePositionFrom){
            throw new \Exception("Can't copy privileges!");
        }
        /*
         * @var ResourcePosition item
         */
        if ($resourcePositionTo) {
            foreach ($resourcePositionTo as $item) {
                $this->objectManager->remove($item);
            }
        }
        $this->objectManager->flush();

        if ($objectFieldDetailsTo){
            foreach ($objectFieldDetailsTo as $item){
                $this->objectManager->remove($item);
            }
        }
        $this->objectManager->flush();

        foreach ($resourcePositionFrom as $item){
            $clone = clone $item;
            $clone->setId(null);
            $clone->setPosition($positionTo);
            $this->objectManager->persist($clone);
        }
        $this->objectManager->flush();

        foreach($objectFieldDetailsFrom as $item){
            $clone = clone $item;
            $clone->setId(null);
            $clone->setPosition($positionTo);
            $this->objectManager->persist($clone);
        }
        $this->objectManager->flush();

    }
}