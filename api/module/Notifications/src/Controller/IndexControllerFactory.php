<?php

declare(strict_types=1);

namespace Notifications\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Notifications\Controller\IndexController;

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
            $container->get(\Notifications\Form\NotificationSearchForm::class),
            $container->get(\Notifications\Table\NotificationTable::class)
        );
    }
}
