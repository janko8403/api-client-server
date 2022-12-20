<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 16.04.18
 * Time: 12:05
 */

namespace Application\Controller;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Users\Service\RegistrationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class ForgottenPasswordControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ForgottenPasswordController(
            $container->get(ObjectManager::class),
            $container->get(PhpRenderer::class),
            $container->get(RegistrationService::class)
        );
    }
}