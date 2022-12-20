<?php

namespace Notifications\Controller;

use Interop\Container\ContainerInterface;
use Notifications\Sms\CallbackService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SmsCallbackControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return SmsCallbackController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SmsCallbackController(
            $container->get(CallbackService::class)
        );
    }
}
