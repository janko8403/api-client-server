<?php


namespace DocumentTemplates\Replacer;


use Commissions\Service\CommisionService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ComissionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Comission($container->get(CommisionService::class));
    }

}