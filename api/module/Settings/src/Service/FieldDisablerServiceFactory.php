<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 09.11.2018
 * Time: 15:00
 */

namespace Settings\Service;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FieldDisablerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $route = $container->get('application')->getMvcEvent()->getRouteMatch();

        return new FieldDisablerService(
            $container->get(PositionVisibilityService::class),
            $container->get(ObjectManager::class),
            $container->get(AuthenticationService::class)->getIdentity(),
            (int)$route->getParam('id')
        );
    }

}