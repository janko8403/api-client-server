<?php

namespace Customers\Controller;

use Customers\Form\CustomerTemplateForm;
use Customers\Service\TemplateService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TemplateControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return TemplateController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TemplateController(
            $container->get(ObjectManager::class),
            $container->get(CustomerTemplateForm::class),
            $container->get(TemplateService::class)
        );
    }
}