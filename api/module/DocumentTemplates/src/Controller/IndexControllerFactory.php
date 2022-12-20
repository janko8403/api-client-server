<?php

namespace DocumentTemplates\Controller;

use DocumentTemplates\Table\DocumentTemplateTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory class for IndexController
 *
 * @package DocumentTemplates\Controller
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(\DocumentTemplates\Form\DocumentTemplateSearchForm::class),
            $container->get(DocumentTemplateTable::class)
        );
    }
}