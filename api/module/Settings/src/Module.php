<?php

namespace Settings;

use Settings\Service\CacheService;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        // set global clear cache event
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        $app->getEventManager()->getSharedManager()->attach('*', CacheService::EVENT_CLEAR, function ($e) use ($sm) {
            $cacheService = $sm->get(CacheService::class);
            $cacheService->clear();
        });
        $app->getEventManager()->getSharedManager()->attach('*', CacheService::EVENT_BUILD_POSITION_VISIBILITY, function ($e) use ($sm) {
            $cacheService = $sm->get(CacheService::class);
            $cacheService->buildRegionCache();
        });
    }
}