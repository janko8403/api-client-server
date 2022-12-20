<?php

namespace Settings\Service;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\EventManager\EventManager;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory class for PositionVisibilityService
 *
 * @package Settings\Service
 */
class PositionVisibilityServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return PositionVisibilityService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PositionVisibilityService(
            $container->get(ObjectManager::class),
            $container->get(EventManager::class),
            $container->get(AbstractAdapter::class)
        );
    }
}