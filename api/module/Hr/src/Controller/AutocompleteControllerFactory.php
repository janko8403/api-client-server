<?php

namespace Hr\Controller;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Dictionary\DictionaryService;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory class for AutocompleteController
 *
 * @package Hr\Controller
 */
class AutocompleteControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return AutocompleteController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AutocompleteController(
            $container->get(ObjectManager::class),
            $container->get(DictionaryService::class)
        );
    }
}