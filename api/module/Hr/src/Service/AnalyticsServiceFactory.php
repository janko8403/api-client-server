<?php


namespace Hr\Service;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AnalyticsServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AnalyticsService(
            $container->get(ObjectManager::class),
            $container->get('config')['analytics']['ua']
        );
    }

}