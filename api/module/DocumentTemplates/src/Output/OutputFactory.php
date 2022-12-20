<?php

namespace DocumentTemplates\Output;

use DocumentTemplates\Entity\DocumentTemplate;
use Interop\Container\ContainerInterface;
use Hr\Content\PdfService;

class OutputFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function factory(string $type): OutputInterface
    {
        switch ($type) {
            case DocumentTemplate::TYPE_MONITORING:
                return new LocalPdf($this->container->get(PdfService::class));
            default:
                throw new \Exception('Unknown document template type: ' . $type);
        }
    }
}