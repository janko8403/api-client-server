<?php

namespace Hr\View\Helper;

use Detection\MobileDetect;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class BreadcrumbsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $breadcrumbs = $container->get('application')->getMvcEvent()->getViewModel()->getVariable('breadcrumbs');

        return new Breadcrumbs(
            $breadcrumbs ?? [],
            $container->get(\Laminas\Mvc\I18n\Translator::class),
            $container->get(MobileDetect::class)
        );
    }
}