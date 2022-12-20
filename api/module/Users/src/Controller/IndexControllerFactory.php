<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.10.17
 * Time: 11:30
 */

namespace Users\Controller;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Users\Form\UserSearchForm;
use Users\Service\UserDataService;
use Users\Table\UserTable;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(ObjectManager::class),
            $container->get(UserTable::class),
            $container->get(UserSearchForm::class),
        );
    }
}