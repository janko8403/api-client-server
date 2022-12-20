<?php


namespace DocumentTemplates\Replacer;


use Commissions\Service\CommissionFetchService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class WorkCertificateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new WorkCertificate(
            $container->get(CommissionFetchService::class),
            $container->get(PhpRenderer::class)
        );
    }
}