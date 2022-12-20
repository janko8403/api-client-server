<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.07.2018
 * Time: 14:48
 */

namespace Hr\Controller;

use Interop\Container\ContainerInterface;
use Users\Service\UserService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ActivityLogControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ActivityLogController($container->get(UserService::class));
    }
}