<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 03.11.17
 * Time: 09:40
 */

namespace Users\Controller;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Users\Form\ChangePasswordForm;
use Users\Service\UserService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class ChangePasswordControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ChangePasswordController(
            $container->get(ObjectManager::class),
            $container->get(PhpRenderer::class),
            $container->get(ChangePasswordForm::class),
            $container->get(UserService::class)
        );
    }
}