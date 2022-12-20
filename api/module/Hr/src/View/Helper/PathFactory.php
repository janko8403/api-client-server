<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 05.06.17
 * Time: 10:46
 */

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PathFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Path($container->get('config')['paths']);
    }
}