<?php

namespace Settings\View\Helper;

use Interop\Container\ContainerInterface;
use Settings\Service\AddressBarService;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AddressBarHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AddressBarHelper(
            $container->get(AddressBarService::class),
            $container->get(AuthenticationService::class)->getIdentity()
        );
    }
}