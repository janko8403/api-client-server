<?php

namespace Hr\Acl;

use Application\Authentication\AuthenticatedIdentity;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Hr\Authentication\AuthenticationService;

class AclServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $configurationPositionId = 0;
        $authenticationService = $container->get(AuthenticationService::class);

        if ($identity = $authenticationService->getIdentity()) {
            $configurationPositionId = $identity['configurationPositionId'] ?? 0;
        } elseif (
            ($identity = $container->get('application')->getMvcEvent()->getParam('Laminas\ApiTools\MvcAuth\Identity'))
            instanceof AuthenticatedIdentity
        ) {
            $configurationPositionId = $identity->getUser()->getConfigurationPosition()->getId();
        }

        $service = new AclService(
            $container->get(ObjectManager::class),
            new \Mobile_Detect(),
            $container->get(AbstractAdapter::class),
            $configurationPositionId
        );

        return $service;
    }

}