<?php

namespace Notifications;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Notifications\Entity\Notification;
use Notifications\Notification\NotificationInterface;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        $app->getEventManager()->getSharedManager()->attach('*', Notification::EVENT_SEND_NOTIFICATION,
            function (EventInterface $e) use ($sm) {
                $notification = $sm->get($e->getTarget());

                if ($notification instanceof NotificationInterface) {
                    $notification->send($e->getParams());
                }
            }
        );
    }
}