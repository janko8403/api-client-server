<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 11.01.18
 * Time: 18:14
 */

namespace Hr\View\Helper;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Hr\Authentication\AuthenticationService;
use Users\Entity\User;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IsAdminFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userId = $container->get(AuthenticationService::class)->getIdentity()['id'];
        $user = $container->get(ObjectManager::class)->find(User::class, $userId);

        return new $requestedName($user);
    }
}