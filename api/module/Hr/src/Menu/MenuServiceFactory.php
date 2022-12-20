<?php

namespace Hr\Menu;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Acl\AclService;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class MenuServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return MenuService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationService = $container->get(AuthenticationService::class);
        $phpRenderer = $container->get(PhpRenderer::class);

        $navigationHelper = $phpRenderer->plugin('navigation');
        $navigationHelper->setAcl($container->get(AclService::class)->getAcl());
        $navigationHelper->setRole((string)($authenticationService->getIdentity()['configurationPositionId'] ?? null));
        $navigationHelper->menu()->setPartial('partials/user-menu');

        return new MenuService(
            $container->get(ObjectManager::class),
            $navigationHelper,
            $container->get('router')
        );
    }
}