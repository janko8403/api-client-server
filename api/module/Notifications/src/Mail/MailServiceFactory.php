<?php

namespace Notifications\Mail;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MailServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @return MailService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MailService(
            $container->get('Config')
        );
    }
}