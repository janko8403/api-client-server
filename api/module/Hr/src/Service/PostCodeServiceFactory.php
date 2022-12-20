<?php

declare(strict_types=1);

namespace Hr\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Hr\Service\PostCodeService;

class PostCodeServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return PostCodeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PostCodeService(
            $container->get(\Doctrine\Persistence\ObjectManager::class),
            $container->get(\Hr\Map\MapService::class)
        );
    }
}
