<?php

namespace Configuration\Resource;

/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 03.07.17
 * Time: 14:34
 */

use Configuration\Entity\Position;
use Configuration\Entity\Resource;
use Configuration\Entity\ResourcePosition;
use Doctrine\Persistence\ObjectManager;

class ResourceService
{
    private ObjectManager $objectManager;

    /**
     * ResourceService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function saveResourceVisibilityForPositions($answers, $resourceId)
    {
        $resource = $this->objectManager->getRepository(Resource::class)->find($resourceId);
        foreach ($answers as $k => $a) {
            /**
             * @var \Configuration\Entity\ResourcePosition
             */
            $resourcePosition = $this->objectManager->getRepository(ResourcePosition::class)->findOneBy(['position' => $k, 'resource' => $resourceId]);

            if (empty($resourcePosition)) {
                $position = $this->objectManager->getRepository(Position::class)->find($k);

                $resourcePosition = new ResourcePosition();
                $resourcePosition->setPosition($position);
                $resourcePosition->setResource($resource);
            }

            $resourcePosition->setSettingSmall($a['small']);
            $resourcePosition->setSettingMedium($a['medium']);
            $resourcePosition->setSettingLarge($a['large']);
            $this->objectManager->persist($resourcePosition);
        }

        $this->objectManager->flush();
    }

    /**
     * @param Resource $resource
     */
    public function saveResource(Resource $resource)
    {
        $positions = $this->objectManager->getRepository(Position::class)->findAll();

        $this->objectManager->persist($resource);
        $this->objectManager->flush();

        foreach ($positions as $postion) {
            /**
             * @var $postion ResourcePosition
             */
            $resourcePositon = new ResourcePosition();
            $resourcePositon->setPosition($postion);
            $resourcePositon->setResource($resource);
            $resourcePositon->setSettingSmall($resource->getSettingSmall());
            $resourcePositon->setSettingMedium($resource->getSettingMedium());
            $resourcePositon->setSettingLarge($resource->getSettingLarge());

            $this->objectManager->persist($resourcePositon);
        }

        $this->objectManager->flush();
    }

    /**
     * @param $resourceId
     * @return array
     */
    public function getPositionsData($resourceId): array
    {
        $data = [];
        $resourcePositions = $this->objectManager->getRepository(ResourcePosition::class)->findBy(['resource' => $resourceId]);

        /**
         * @var $pos ResourcePosition
         */
        foreach ($resourcePositions as $pos) {
            $data[$pos->getPosition()->getId()]['small'] = $pos->getSettingSmall();
            $data[$pos->getPosition()->getId()]['medium'] = $pos->getSettingMedium();
            $data[$pos->getPosition()->getId()]['large'] = $pos->getSettingLarge();
        }

        return $data;
    }

    /**
     * Gets monitoring categories in configuration order.
     *
     * @return array
     */
    public function getCustomerMonitoringGroups(): array
    {
        $temp = [];
        $data = $this->objectManager->getRepository(Resource::class)->getCustomerMonitoringGroups();
        foreach ($data as $d) {
            $temp[$d->getMonitoringCategory()->getId()] = ['label' => $d->getLabel(), 'sequence' => $d->getSequence()];
        }

        return $temp;
    }

    /**
     * Gets monitoring categories for user in configuration order.
     *
     * @return array
     */
    public function getUserMonitoringGroups(): array
    {
        $temp = [];
        $data = $this->objectManager->getRepository(Resource::class)->getUserMonitoringGroups();
        foreach ($data as $d) {
            $temp[$d->getMonitoringCategory()->getId()] = ['label' => $d->getLabel(), 'sequence' => $d->getSequence()];
        }

        return $temp;
    }

    /**
     * Gets customer application resource names for given position.
     *
     * @param int $positionId
     * @return array
     */
    public function getResourcesForCustomerApplication(int $positionId): array
    {
        $data = $this->objectManager->getRepository(Resource::class)->getResourcesForCustomerApplication($positionId);

        return array_map(fn($r) => $r->getName(), $data);
    }
}