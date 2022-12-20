<?php

namespace Hr\Module;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ModuleServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return ModuleService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ModuleService($container->get(AdapterInterface::class));
    }
}