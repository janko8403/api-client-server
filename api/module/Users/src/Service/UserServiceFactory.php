<?php

namespace Users\Service;

use Hr\Authentication\AuthenticationService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return UserService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserService(
            $container->get(\Doctrine\ORM\EntityManager::class),
            $container->get(AuthenticationService::class)->getIdentity()['id'] ?? null
        );
    }
}