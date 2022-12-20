<?php

namespace Hr\View\Helper;

use Hr\Module\ModuleService;
use Laminas\View\Helper\AbstractHelper;

class ModuleActive extends AbstractHelper
{
    /**
     * @var ModuleService
     */
    private $moduleService;

    /**
     * ModuleActive constructor.
     *
     * @param ModuleService $moduleService
     */
    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function __invoke(string $moduleName)
    {
        return $this->moduleService->isActive($moduleName);
    }
}