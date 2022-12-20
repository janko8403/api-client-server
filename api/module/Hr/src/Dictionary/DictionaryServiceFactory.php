<?php

namespace Hr\Dictionary;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DictionaryServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return DictionaryService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DictionaryService(
            $container->get(ObjectManager::class)
        );
    }
}