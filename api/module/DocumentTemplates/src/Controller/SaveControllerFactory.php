<?php

namespace DocumentTemplates\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory class for SaveController
 *
 * @package DocumentTemplates\Controller
 */
class SaveControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return SaveController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SaveController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(\DocumentTemplates\Form\DocumentTemplateForm::class)
        );
    }
}