<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Hr;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Hr\Personalization\PersonalizationAwareInterface;
use Hr\Personalization\PersonalizationService;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, BootstrapListenerInterface, ServiceProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $conn = $e->getApplication()->getServiceManager()->get('doctrine.connection.orm_default');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'OverrideAuthenticationService' => function (ContainerInterface $container) {
                    return new class extends \Laminas\Authentication\AuthenticationService {
                        public function getIdentity()
                        {
                            return (array)parent::getIdentity();
                        }
                    };
                },
            ],
            'initializers' => [
                function (ContainerInterface $container, $instance) {
                    if ($instance instanceof AdapterAwareInterface) {
                        try {
                            $instance->setDbAdapter($container->get(AdapterInterface::class));
                        } catch (\Exception $e) {

                        }
                    }
                    if ($instance instanceof PersonalizationAwareInterface) {
                        $instance->setPersonalizationService($container->get(PersonalizationService::class));
                    }
                    if ($instance instanceof ObjectManagerAwareInterface) {
                        $instance->setObjectManager($container->get('doctrine.entitymanager.orm_default'));
                    }
                },
            ],
            'aliases' => [
                'Laminas\Authentication\AuthenticationService' => 'OverrideAuthenticationService',
            ],
        ];
    }
}