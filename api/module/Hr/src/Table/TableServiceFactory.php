<?php

namespace Hr\Table;

use Configuration\Object\ObjectService;
use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TableServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return TableService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(ObjectService::class),
            $container->get(AbstractAdapter::class)
        );
    }
}