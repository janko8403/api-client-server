<?php

namespace Hr\Form;

use Configuration\Object\ObjectService;
use Interop\Container\ContainerInterface;
use Settings\Service\FieldDisablerService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FormServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return FormService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $route = $container->get('application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();

        return new FormService(
            $container->get(ObjectService::class),
            $container->get(AbstractAdapter::class),
            $container->get(FieldDisablerService::class),
            explode('/', $route, 2)[0]
        );
    }
}