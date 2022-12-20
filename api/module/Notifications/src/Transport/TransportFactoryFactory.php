<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 19:21
 */

namespace Notifications\Transport;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TransportFactoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return TransportFactory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TransportFactory($container);
    }
}