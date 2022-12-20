<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.07.2018
 * Time: 17:55
 */

namespace Settings\Service;


use Doctrine\Persistence\ObjectManager;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Hr\Entity\RegionSubregionJoint;
use Hr\Entity\SubregionMacroregionJoint;

class RegionService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * RegionService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Gets list of macroregions.
     *
     * @return array
     */
    public function getMacroRegions(): array
    {
        return $this->objectManager->getRepository(DictionaryDetails::class)->findBy(
            ['dictionary' => Dictionary::DIC_MACROREGIONS],
            ['name' => 'ASC']
        );
    }

    /**
     * Gets list of subregions (groupped by macroregion).
     *
     * @return array
     */
    public function getSubregions(): array
    {
        $data = [];
        $subregions = $this->objectManager->getRepository(SubregionMacroregionJoint::class)->findAll();

        foreach ($subregions as $subregion) {
            $macroregionId = $subregion->getMacroregion()->getId();
            if (!isset($data[$macroregionId])) {
                $data[$macroregionId] = [];
            }

            $data[$macroregionId][] = $subregion->getSubregion();
        }

        return $data;
    }

    /**
     * Gets list of regions (groupped by subregion).
     *
     * @return array
     */
    public function getRegions(): array
    {
        $data = [];
        $regions = $this->objectManager->getRepository(RegionSubregionJoint::class)->findAll();

        foreach ($regions as $region) {
            $subregionId = $region->getSubregion()->getId();
            if (!isset($data[$subregionId])) {
                $data[$subregionId] = [];
            }

            $data[$subregionId][] = $region->getRegion();
        }

        return $data;
    }

    /**
     * Gets unassigned regions.
     *
     * @return array
     */
    public function getUnassignedRegions(): array
    {
        return $this->objectManager->getRepository(RegionSubregionJoint::class)->getUnassignedRegions();
    }

    /**
     * Gets unassigned subregions.
     *
     * @return array
     */
    public function getUnassignedSubregions(): array
    {
        return $this->objectManager->getRepository(SubregionMacroregionJoint::class)->getUnassignedSubregions();
    }

    /**
     * Assigns subregion to a given macroregion.
     *
     * @param $subregionId
     * @param $macroregionId
     */
    public function assignSubregionToMacroregion($subregionId, $macroregionId)
    {
        if (empty($macroregionId)) {
            $this->unassignSubregionFromMacroregion($subregionId);
        } else {
            $this->unassignSubregionFromMacroregion($subregionId);

            $subregion = $this->objectManager->find(DictionaryDetails::class, $subregionId);
            $macroregion = $this->objectManager->find(DictionaryDetails::class, $macroregionId);

            $smj = new SubregionMacroregionJoint();
            $smj->setSubregion($subregion);
            $smj->setMacroregion($macroregion);
            $this->objectManager->persist($smj);
            $this->objectManager->flush();
        }
    }

    /**
     * Unassignes subregion from macroregion.
     *
     * @param $subregionId
     */
    public function unassignSubregionFromMacroregion($subregionId)
    {
        $joints = $this->objectManager->getRepository(SubregionMacroregionJoint::class)
            ->findBy(['subregion' => $subregionId]);
        foreach ($joints as $joint) {
            $this->objectManager->remove($joint);
        }
        $this->objectManager->flush();
    }

    /**
     * Assignes region to a given subregion.
     *
     * @param $regionId
     * @param $subregionId
     */
    public function assignRegionToSubregion($regionId, $subregionId)
    {
        if (empty($subregionId)) {
            $this->unassignRegionFromSubregion($regionId);
        } else {
            $this->unassignRegionFromSubregion($regionId);

            $region = $this->objectManager->find(DictionaryDetails::class, $regionId);
            $subregion = $this->objectManager->find(DictionaryDetails::class, $subregionId);

            $rsj = new RegionSubregionJoint();
            $rsj->setRegion($region);
            $rsj->setSubregion($subregion);
            $this->objectManager->persist($rsj);
            $this->objectManager->flush();
        }
    }

    /**
     * Unassignes region from subregion.
     *
     * @param $regionId
     */
    public function unassignRegionFromSubregion($regionId)
    {
        $joints = $this->objectManager->getRepository(RegionSubregionJoint::class)
            ->findBy(['region' => $regionId]);
        foreach ($joints as $joint) {
            $this->objectManager->remove($joint);
        }
        $this->objectManager->flush();
    }

    /**
     * Gets macroregion id for given region.
     *
     * @param int $region
     * @return int
     * @throws \Exception
     */
    public function getMacroregionForRegion(int $region): int
    {
        $macroregion = $this->objectManager->getRepository(DictionaryDetails::class)
            ->getMacroregionForRegion($region);

        if (isset($macroregion[0])) {
            return $macroregion[0]['id'];
        }

        throw new \Exception("Unknown macroregion for subregion `$region`");
    }
}