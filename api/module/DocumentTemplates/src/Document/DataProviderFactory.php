<?php

namespace DocumentTemplates\Document;

use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Replacer\Monitoring;
use Interop\Container\ContainerInterface;

class DataProviderFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function factory(string $type): DataProvider
    {
        $replacerTypes = [
            DocumentTemplate::TYPE_MONITORING => [
                Monitoring::class,
            ],
        ];

        if (!isset($replacerTypes[$type])) {
            throw new \Exception('Unknown document template type: ' . $type);
        }

        $replacers = [];
        foreach ($replacerTypes[$type] as $replacerType) {
            $replacers[] = $this->container->get($replacerType);
        }

        return new DataProvider($replacers);
    }

}