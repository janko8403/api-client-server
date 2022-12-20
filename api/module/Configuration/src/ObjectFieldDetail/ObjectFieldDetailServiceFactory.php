<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 10.07.17
 * Time: 18:48
 */

namespace Configuration\ObjectFieldDetail;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ObjectFieldDetailServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ObjectFieldDetailService(
            $container->get(ObjectManager::class)
        );
    }
}