<?php

namespace Notifications\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Notifications\Controller\SmsHistoryController;

class SmsHistoryControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return SmsHistoryController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SmsHistoryController(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(\Notifications\Table\SmsHistoryTable::class),
            $container->get(\Notifications\Form\SmsHistorySearchForm::class)
        );
    }
}
