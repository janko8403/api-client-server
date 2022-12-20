<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Hr\Acl\AclService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class OperationsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Operations(
            $container->get(AclService::class)
        );
    }
}
