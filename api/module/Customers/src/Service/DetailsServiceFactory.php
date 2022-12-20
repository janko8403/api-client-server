<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 29.01.18
 * Time: 17:35
 */

namespace Customers\Service;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Acl\AclService;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DetailsServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DetailsService(
            $container->get(ObjectManager::class),
            $container->get(AclService::class),
            $container->get(AuthenticationService::class)->getIdentity()['configurationPositionId']
        );
    }

}