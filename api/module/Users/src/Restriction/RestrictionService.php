<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 23.05.2018
 * Time: 14:53
 */

namespace Users\Restriction;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;

class RestrictionService
{
    /**
     * @var RestrictionFactory
     */
    private $restrictionFactory;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var array
     */
    private $config;

    /**
     * RestrictionService constructor.
     * @param RestrictionFactory $restrictionFactory
     * @param ObjectManager $objectManager
     * @param array $config
     */
    public function __construct(RestrictionFactory $restrictionFactory, ObjectManager $objectManager, array $config)
    {
        $this->restrictionFactory = $restrictionFactory;
        $this->objectManager = $objectManager;
        $this->config = $config;
    }

    public function process(array $restrictions, QueryBuilder $queryBuilder, array $params = [])
    {
        foreach ($restrictions as $restriction) {
            $obj = $this->restrictionFactory->factory($restriction->getKey());

            if ($obj instanceof RestrictionQbInterface) {
                $obj->add($queryBuilder, $params);
            }
        }
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}