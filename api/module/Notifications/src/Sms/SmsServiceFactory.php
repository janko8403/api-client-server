<?php
/**
 * Created by PhpStorm.
 * User: pawelz
 * Date: 2019-03-06
 * Time: 15:58
 */

namespace Notifications\Sms;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SmsServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SmsService(
            $container->get(ObjectManager::class),
            $container->get('config')['sms_api_token'],
            $container->get('config')['notifications']['catch_all']['sms'] ?? []
        );
    }

}