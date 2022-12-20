<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Hr\Module\ModuleService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ModuleActiveFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $className = '\\' . $requestedName;

        return new $className($container->get(ModuleService::class));
    }
}