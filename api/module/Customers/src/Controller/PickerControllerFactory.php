<?php

namespace Customers\Controller;

use Customers\Controller\PickerController;
use Customers\Form\CustomerPickerSearchForm;
use Customers\Table\CustomerTable;
use Interop\Container\ContainerInterface;
use Settings\Service\PositionVisibilityService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PickerControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return PickerController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PickerController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(CustomerPickerSearchForm::class),
            $container->get(CustomerTable::class),
            $container->get(\Laminas\View\Renderer\PhpRenderer::class),
            $container->get(\Hr\RecordPicker\RecordPickerService::class),
            $container->get(PositionVisibilityService::class)
        );
    }
}