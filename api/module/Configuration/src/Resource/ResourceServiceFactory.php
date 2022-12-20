<?php

namespace Configuration\Resource;
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 03.07.17
 * Time: 14:35
 */
use Configuration\Resource\ResourceService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ResourceServiceFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Laminas\ServiceManager\Exception\ServiceNotFoundException if unable to resolve the service.
     * @throws \Laminas\ServiceManager\Exception\ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws \Interop\Container\Exception\ContainerException if any other error occurs
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ResourceService($container->get(\Doctrine\Persistence\ObjectManager::class));
    }
}