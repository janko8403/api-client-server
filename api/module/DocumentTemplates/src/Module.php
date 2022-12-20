<?php

namespace DocumentTemplates;

use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Service\DocumentInterface;
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
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        $app->getEventManager()->getSharedManager()->attach('*', DocumentTemplate::EVENT_GENERATE_DOCUMENT,
            function (EventInterface $e) use ($sm) {
                $document = $sm->get($e->getTarget());

                if ($document instanceof DocumentInterface) {
                    return $document->generate($e->getParams());
                }
            }
        );
    }
}