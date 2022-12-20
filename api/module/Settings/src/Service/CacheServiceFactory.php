<?php


namespace Settings\Service;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CacheServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CacheService($container->get(EntityManagerInterface::class), $container->get(AbstractAdapter::class));
    }

}