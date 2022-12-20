<?php

declare(strict_types=1);

namespace Customers\Table;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CustomerTableFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return CustomerTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CustomerTable(
            $container->get(\Configuration\Object\ObjectService::class),
            $container->get(\Laminas\Cache\Storage\Adapter\AbstractAdapter::class),
        );
    }
}
