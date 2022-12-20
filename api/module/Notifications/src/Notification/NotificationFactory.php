<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 23:39
 */

namespace Notifications\Notification;

use Doctrine\Persistence\ObjectManager;
use Hr\Config\ConfigAwareInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Notifications\RecipientStrategy\RecipientFactory;
use Notifications\Transport\TransportFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NotificationFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return NotificationInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new $requestedName(
            $container->get(ObjectManager::class),
            $container->get(RecipientFactory::class),
            $container->get(TransportFactory::class)
        );

        if ($instance instanceof ConfigAwareInterface) {
            $instance->setConfig($container->get('config'));
        }

        return $instance;
    }
}