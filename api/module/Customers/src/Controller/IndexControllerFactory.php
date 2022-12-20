<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 23.07.17
 * Time: 22:47
 */

namespace Customers\Controller;


use Customers\Form\CustomerPickerSearchForm;
use Customers\Service\ExportService;
use Customers\Table\CustomerTable;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Settings\Service\PositionVisibilityService;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(ObjectManager::class),
            $container->get(CustomerTable::class),
            $container->get(CustomerPickerSearchForm::class),
            $container->get(PositionVisibilityService::class),
        );
    }
}