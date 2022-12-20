<?php


namespace DocumentTemplates\Replacer;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;

class CommissionSummaryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CommissionSummary($container->get(PhpRenderer::class));
    }

}