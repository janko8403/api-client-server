<?php

namespace AssemblyOrders;

use AssemblyOrders\Notification\OrderAcceptedSMS;
use Laminas\EventManager\EventInterface;
use Laminas\ServiceManager\ServiceManager;

class Module
{
    const ASSEMBLY_ORDER_ACCEPTED = 'assemblyOrderAccepted';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        /** @var ServiceManager $serviceManager */
        $serviceManager = $app->getServiceManager();
        $events = $app->getEventManager()->getSharedManager();

        $events->attach('*',
            self::ASSEMBLY_ORDER_ACCEPTED,
            function ($e) use ($serviceManager) {
                $serviceManager->get(OrderAcceptedSMS::class)->send($e->getTarget());
            }
        );
    }
}
