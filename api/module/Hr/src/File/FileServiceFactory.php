<?php

namespace Hr\File;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Hr\Table\TableService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FileServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return FileService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new FileService(
            $container->get(EntityManager::class),
            $container->get('Config')['paths']
        );
    }
}