<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.10.2016
 * Time: 16:26
 */

namespace Hr\Authorization;


use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService as LaminasAuthenticationService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthorizationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new AuthorizationService(
            $container->get(LaminasAuthenticationService::class),
            $container->get(AbstractAdapter::class)
        );
        $service->setDbAdapter($container->get(AdapterInterface::class));

        return $service;
    }
}