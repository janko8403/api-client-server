<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 26.07.17
 * Time: 15:48
 */

namespace Customers\Controller;


use Customers\Form\CustomerEditForm;
use Customers\Form\CustomerForm;
use Customers\Service\CustomerService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SaveControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SaveController(
            $container->get(ObjectManager::class),
            $container->get(CustomerForm::class),
            $container->get(CustomerService::class),
            $container->get(CustomerEditForm::class)
        );
    }
}