<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 04.07.17
 * Time: 16:14
 */

namespace Configuration\Controller;


use Configuration\ObjectField\ObjectFieldService;
use Configuration\ObjectFieldDetail\ObjectFieldDetailService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class ObjectControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ObjectController(
            $container->get(ObjectManager::class),
            $container->get(PhpRenderer::class),
            $container->get(ObjectFieldDetailService::class),
            $container->get(ObjectFieldService::class)
        );
    }
}