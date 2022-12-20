<?php

declare(strict_types=1);

namespace Hr\Mvc\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Hr\Mvc\Controller\Plugin\Guard;

class GuardFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return Guard
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Guard($container->get(\Hr\Acl\AclService::class));
    }
}
