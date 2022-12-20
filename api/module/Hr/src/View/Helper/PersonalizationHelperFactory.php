<?php

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Description of PersonalizationHelperFactory
 *
 * @author daniel
 */
class PersonalizationHelperFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PersonalizationHelper($container->get(\Hr\Personalization\PersonalizationService::class));
    }
}
