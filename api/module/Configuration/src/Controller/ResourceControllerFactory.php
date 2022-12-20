<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.06.17
 * Time: 15:29
 */

namespace Configuration\Controller;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Configuration\Resource\ResourceService;
use Laminas\View\Renderer\PhpRenderer;

class ResourceControllerFactory implements FactoryInterface
{
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
      return new ResourceController(
          $container->get(ObjectManager::class),
          $container->get(ResourceService::class),
          $container->get(PhpRenderer::class)
      );
  }
}