<?php

namespace Hr\Mvc\Controller\Plugin;

use Hr\Personalization\PersonalizationService;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class PersonalizationPlugin extends AbstractPlugin
{
    /**
     * @var PersonalizationService
     */
    private $service;

    /**
     * PersonalizationPlugin constructor.
     *
     * @param PersonalizationService $service
     */
    public function __construct(PersonalizationService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves Personalization service.
     *
     * @param string $name
     * @return bool
     */
    public function __invoke(string $name)
    {
        return $this->service->active($name);
    }
}