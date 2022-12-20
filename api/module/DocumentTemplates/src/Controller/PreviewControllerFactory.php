<?php

namespace DocumentTemplates\Controller;

use DocumentTemplates\Service\DocumentService;
use Interop\Container\ContainerInterface;
use Hr\Content\PdfService;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory class for PreviewControllerFactory
 *
 * @package DocumentTemplates\Controller
 */
class PreviewControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return PreviewController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PreviewController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(PdfService::class),
            $container->get(DocumentService::class)
        );
    }
}