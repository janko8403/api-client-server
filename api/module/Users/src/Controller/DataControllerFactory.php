<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 08.11.17
 * Time: 12:54
 */

namespace Users\Controller;

use Commissions\Service\CommissionFetchService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Messages\Service\MessageService;
use Hr\File\FileService;
use Users\Service\BlockedDataService;
use Users\Service\UserDataService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DataControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DataController(
            $container->get(ObjectManager::class),
            $container->get(UserDataService::class),
            $container->get(FileService::class),
            $container->get(BlockedDataService::class),
            $container->get(CommissionFetchService::class)
        );
    }
}