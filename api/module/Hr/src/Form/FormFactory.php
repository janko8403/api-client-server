<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.2016
 * Time: 13:27
 */

namespace Hr\Form;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Settings\Service\PositionVisibilityService;
use Hr\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(ObjectManager::class);
        $identity = $container->get(AuthenticationService::class)->getIdentity();

        /** @var BaseForm $form */
        if (is_subclass_of($requestedName, PositionVisibilityAwareInterface::class)) {
            $form = new $requestedName($em, $container->get(PositionVisibilityService::class), $identity);
        } else {
            $form = new $requestedName($em);
        }

        $formService = $container->get(FormService::class);
        $formService->process($form);

        return $form;
    }
}