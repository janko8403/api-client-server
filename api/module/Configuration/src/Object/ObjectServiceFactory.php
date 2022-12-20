<?php

namespace Configuration\Object;

use Configuration\Entity\Position;
use Configuration\Entity\Resource;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Authentication\AuthenticationService;
use Hr\Personalization\PersonalizationService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ObjectServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return ObjectService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $positionId = $container->get(AuthenticationService::class)->getIdentity()['configurationPositionId'];
        $objectManager = $container->get(ObjectManager::class);
        $position = $objectManager->find(Position::class, $positionId);

        $route = $container->get('application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $resource = $objectManager->getRepository(Resource::class)->findChildByRoute($route);

        return new ObjectService(
            $objectManager,
            $container->get('mobileDetect'),
            $position,
            $resource
        );
    }
}