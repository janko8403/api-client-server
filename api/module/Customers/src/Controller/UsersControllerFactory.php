<?php

declare(strict_types=1);

namespace Customers\Controller;

use Customers\Controller\UsersController;
use Customers\Form\AddUserForm;
use Customers\Form\UserForm;
use Customers\Service\UserRelationService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UsersControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return UsersController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UsersController(
            $container->get(ObjectManager::class),
            $container->get(UserForm::class),
            $container->get(UserRelationService::class),
            $container->get(AddUserForm::class)
        );
    }
}
