<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 12.07.17
 * Time: 12:24
 */

namespace Configuration\ObjectField;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ObjectFieldServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ObjectFieldService(
            $container->get(ObjectManager::class)
        );
    }
}