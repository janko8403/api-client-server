<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.10.17
 * Time: 15:04
 */

namespace Users\Controller;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Users\Form\UserForm;
use Users\Service\UserService;

class SaveControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SaveController(
            $container->get(ObjectManager::class),
            $container->get(UserForm::class),
            $container->get(UserService::class)
        );
    }
}