<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.11.2018
 * Time: 16:11
 */

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Users\Service\UserService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IsSubordinateOfFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IsSubordinateOf($container->get(UserService::class));
    }

}