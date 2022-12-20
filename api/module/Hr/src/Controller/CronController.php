<?php

namespace Hr\Controller;

use Hr\Service\CronService;

class CronController extends BaseController
{
    private CronService $service;

    public function __construct(CronService $service)
    {
        $this->addBreadcrumbsPart('Crony');
        $this->service = $service;
    }

    public function indexAction()
    {
        return ['html' => $this->service->fetch()];
    }

    public function executeAction()
    {
        if ($this->getRequest()->isPost()) {
            $command = $this->params()->fromPost('command');
            $command = $this->service->normalizeIID($command);

            exec($command);
        }

        return $this->getResponse();
    }
}