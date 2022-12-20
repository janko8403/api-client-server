<?php


namespace Users\Service;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;

class LoginServiceFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoginService(
            $container->get(ObjectManager::class),
            $container->get('config')
        );
    }
}