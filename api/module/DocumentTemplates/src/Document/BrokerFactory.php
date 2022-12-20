<?php

namespace DocumentTemplates\Document;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class BrokerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Broker
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Broker(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(\DocumentTemplates\Document\DataProviderFactory::class),
            $container->get(\DocumentTemplates\Output\OutputFactory::class)
        );
    }
}
