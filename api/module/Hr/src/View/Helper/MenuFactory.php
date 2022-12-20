<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Hr\Authentication\AuthenticationService;
use Hr\Menu\MenuService;

class MenuFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Menu(
            $container->get(MenuService::class),
            $container->get(AbstractAdapter::class),
            new \Mobile_Detect(),
            $container->get(AuthenticationService::class)->getIdentity()['configurationPositionId'] ?? 0
        );
    }
}