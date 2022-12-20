<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 23.05.2018
 * Time: 15:25
 */

namespace Users\Restriction;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RestrictionServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RestrictionService(
            $container->get(RestrictionFactory::class),
            $container->get(ObjectManager::class),
            $container->get('config')['user_restrictions'] ?? []
        );
    }
}