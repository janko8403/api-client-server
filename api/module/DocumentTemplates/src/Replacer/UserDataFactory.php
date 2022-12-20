<?php

namespace DocumentTemplates\Replacer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserDataFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return UserData
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserData($container->get(\Users\Service\UserDataService::class));
    }
}
