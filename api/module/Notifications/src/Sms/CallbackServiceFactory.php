<?php

namespace Notifications\Sms;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Notifications\Sms\CallbackService;

class CallbackServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return CallbackService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CallbackService(
            $container->get(\Notifications\Sms\SmsService::class),
            $container->get(\Notifications\SmsCallback\CallbackFactory::class)
        );
    }
}
