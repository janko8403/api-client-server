<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.02.18
 * Time: 14:42
 */

namespace Hr\View\Helper;


use Interop\Container\ContainerInterface;
use Hr\Acl\AclService;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IsAllowedFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IsAllowed(
            $container->get(AclService::class),
            $container->get(AuthenticationService::class)->getIdentity()['configurationPositionId'] ?? 0
        );
    }
}