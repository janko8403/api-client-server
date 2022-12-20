<?php


namespace DocumentTemplates\Replacer;


use Interop\Container\ContainerInterface;
use Marketing\Service\AgreementService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserAgreementFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserAgreement($container->get(AgreementService::class));
    }

}