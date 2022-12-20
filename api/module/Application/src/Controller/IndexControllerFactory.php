<?php

namespace Application\Controller;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Hr\Authentication\AuthenticationService;
use Users\Service\UserService;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(AuthenticationService::class),
            $container->get(ObjectManager::class),
            $container->get(UserService::class),
        );
    }
}