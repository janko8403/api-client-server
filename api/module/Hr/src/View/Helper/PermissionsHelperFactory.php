<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Description of PermissionsHelperFactory
 *
 * @author daniel
 */
class PermissionsHelperFactory implements FactoryInterface
{

    /**
     *
     * @param ContainerInterface $container
     * @param type               $requestedName
     * @param array              $options
     * @return \Hr\View\Helper\PermissionsHelperHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        return new PermissionsHelper(
            $container->get(\Hr\Authorization\AuthorizationService::class)
        );
    }
}
