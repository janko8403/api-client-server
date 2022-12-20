<?php


namespace Users\Table;


use Configuration\Object\ObjectService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserTableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserTable(
            $container->get(ObjectService::class),
            $container->get(AbstractAdapter::class),
            $container->get(ObjectManager::class),
        );
    }
}