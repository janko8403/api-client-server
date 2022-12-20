<?php

namespace Users\Controller;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\RecordPicker\RecordPickerService;
use Users\Form\UserSearchForm;
use Users\Table\UserTable;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

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
            $container->get(ObjectManager::class),
            $container->get(UserTable::class),
            $container->get(PhpRenderer::class),
            $container->get(RecordPickerService::class),
            $container->get(UserSearchForm::class)
        );
    }
}