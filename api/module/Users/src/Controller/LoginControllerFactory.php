<?php

namespace Users\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Users\Controller\LoginController;

class LoginControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return LoginController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoginController(
            $container->get(\Users\Service\LoginService::class),
            $container->get(\Laminas\View\Renderer\PhpRenderer::class)
        );
    }
}
