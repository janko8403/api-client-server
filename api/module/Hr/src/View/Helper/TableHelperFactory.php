<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Hr\Acl\AclService;
use Laminas\Mvc\I18n\Translator;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TableHelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return TableHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TableHelper(
            $container->get(Translator::class),
            $container->get(AclService::class)
        );
    }
}