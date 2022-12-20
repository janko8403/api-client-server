<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 29.08.17
 * Time: 13:52
 */

namespace Hr\View\Helper;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Hr\Acl\AclService;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AclFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Acl($container->get(AclService::class));
    }
}