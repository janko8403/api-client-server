<?php

namespace Hr\Map;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MapServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MapService(
            $container->get(ObjectManager::class),
            $container->get('config')['google_api']['javascript'] ?? ''
        );
    }

}