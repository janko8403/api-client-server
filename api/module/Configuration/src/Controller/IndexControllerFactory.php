<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 27.06.17
 * Time: 15:50
 */

namespace Configuration\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(\Configuration\Form\MenuSearchFrom::class)
        );
    }
}