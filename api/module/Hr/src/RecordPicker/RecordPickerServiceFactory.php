<?php

namespace Hr\RecordPicker;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Hr\Authentication\AuthenticationService;
use Hr\Table\TableService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RecordPickerServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return TableService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RecordPickerService(
            $container->get(EntityManager::class),
            $container->get(AuthenticationService::class)->getIdentity()['id']
        );
    }
}