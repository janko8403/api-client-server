<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.2016
 * Time: 16:44
 */

namespace Hr\Cache;

use Interop\Container\ContainerInterface;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CacheStorageFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $storageFactory = $container->get(StorageAdapterFactoryInterface::class);

        return $storageFactory->createFromArrayConfiguration($container->get('config')['cache']);
    }
}
