<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class HeadFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $version = $container->get('config')['view_manager']['app_version'] ?? 1;

        return new $requestedName($version);
    }
}