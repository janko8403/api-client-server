<?php

namespace Hr\Mvc\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Hr\Personalization\PersonalizationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PersonalizationPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PersonalizationPlugin($container->get(PersonalizationService::class));
    }
}