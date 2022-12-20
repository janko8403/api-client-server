<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.10.2016
 * Time: 14:02
 */

namespace Hr\Db\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class InstanceAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Adapter($options);
    }
}