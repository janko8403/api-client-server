<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UrlParamFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return TableHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UrlParam(
            $container->get('Application')->getMvcEvent()->getRouteMatch(),
            $container->get('Request')->getQuery()->toArray()
        );
    }
}