<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.07.2017
 * Time: 18:17
 */

namespace Settings\Service;

use Doctrine\ORM\EntityManagerInterface;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Hr\Entity\RegionSubregionJoint;
use Hr\Entity\SubregionMacroregionJoint;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;

/**
 * Class CacheService
 * Usage: $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);
 *
 * @package Settings\Service
 */
class CacheService
{
    // cache events
    const EVENT_CLEAR = __NAMESPACE__ . '_CLEAR_CACHE';
    const EVENT_BUILD_POSITION_VISIBILITY = __NAMESPACE__ . '_BUILD_POSITION_VISIBILITY';

    // cache names
    const CACHE_REGIONS = 'CACHE_REGIONS';
    const CACHE_REGIONS_SUBORDINATES = 'CACHE_REGIONS_SUBORDINATES';

    /**
     * @var EntityManagerInterface
     */
    private $objectManager;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * CacheService constructor.
     *
     * @param EntityManagerInterface $objectManager
     * @param AbstractAdapter        $cacheAdapter
     */
    public function __construct(EntityManagerInterface $objectManager, AbstractAdapter $cacheAdapter)
    {
        $this->objectManager = $objectManager;
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * Clears cache.
     */
    public function clear()
    {
        $path = getcwd() . '/data/cache/*';
        exec("rm -rf $path");
    }

    /**
     * Builds regions cache.
     */
    public function buildRegionCache()
    {
        $regions = [];
        $temp = $this->objectManager->getRepository(DictionaryDetails::class)->findBy(['dictionary' => Dictionary::DIC_REGIONS]);
        foreach ($temp as $region) {
            $regions[$region->getId()] = ['subregion' => [], 'macroregion' => []];

            $subregion = $this->objectManager->getRepository(RegionSubregionJoint::class)->findOneBy(['region' => $region]);
            if (!empty($subregion)) {
                // find all regions in subregion
                $subregionRegions = $this->objectManager->getRepository(RegionSubregionJoint::class)->findBy(['subregion' => $subregion->getSubregion()]);
                foreach ($subregionRegions as $subregionRegion) {
                    $regions[$region->getId()]['subregion'][] = $subregionRegion->getRegion()->getId();
                }

                // find all regions in macroregion
                $regions[$region->getId()]['macroregion'] = $this->objectManager->getRepository(SubregionMacroregionJoint::class)->getRegionsForMacroregionContainingSubregion($subregion->getSubregion()->getId());
            }
        }

        $this->cacheAdapter->setItem(self::CACHE_REGIONS, $regions);
    }
}