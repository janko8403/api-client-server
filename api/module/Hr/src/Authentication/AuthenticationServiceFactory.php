<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.10.2016
 * Time: 16:26
 */

namespace Hr\Authentication;


use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService as LaminasAuthenticationService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new AuthenticationService(
            $container->get(LaminasAuthenticationService::class),
            $container->get('Response')
        );

        try {
            $service->setDbAdapter($container->get(AdapterInterface::class));
        } catch (\Exception $e) {

        }

        return $service;
    }

}