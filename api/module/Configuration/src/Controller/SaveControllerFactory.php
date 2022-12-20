<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.06.17
 * Time: 15:53
 */

namespace Configuration\Controller;


use Configuration\Form\ResourceForm;
use Configuration\Resource\ResourceService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class SaveControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SaveController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(ResourceForm::class),
            $container->get(\Laminas\Mvc\I18n\Translator::class),
            $container->get(ResourceService::class),
            $container->get(PhpRenderer::class)
        );
    }
}