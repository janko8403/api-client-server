<?php
/**
 * mdomarecki
 *
 * configuration positions are selected (activ and inactive) to configuration because
 * they can change their activity statuses
 *
 */
namespace Configuration;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}