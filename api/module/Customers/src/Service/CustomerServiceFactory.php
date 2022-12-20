<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.07.17
 * Time: 11:09
 */

namespace Customers\Service;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CustomerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CustomerService(
            $container->get(ObjectManager::class),
            $container->get(TemplateService::class),
            $container->get(AuthenticationService::class)
        );
    }
}