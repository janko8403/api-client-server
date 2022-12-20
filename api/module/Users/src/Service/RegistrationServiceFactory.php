<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 12.10.17
 * Time: 11:51
 */

namespace Users\Service;

use Commissions\Service\CommissionFetchService;
use Doctrine\Persistence\ObjectManager;
use Features\Service\FeatureService;
use Interop\Container\ContainerInterface;
use Laminas\EventManager\EventManager;
use Laminas\Mvc\I18n\Translator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Notifications\Sms\SmsService;
use Hr\Service\PostCodeService;

class RegistrationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RegistrationService(
            $container->get(ObjectManager::class),
            $container->get(EventManager::class),
            $container->get(Translator::class),
            $container->get(SmsService::class),
            $container->get(FeatureService::class),
            $container->get(PostCodeService::class),
            $container->get(CommissionFetchService::class)
        );
    }

}