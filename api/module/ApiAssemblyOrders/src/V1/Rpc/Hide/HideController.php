<?php

namespace ApiAssemblyOrders\V1\Rpc\Hide;

use ApiAssemblyOrders\V1\Service\HideService;
use Laminas\Mvc\Controller\AbstractActionController;

class HideController extends AbstractActionController
{
    public function __construct(private HideService $service)
    {
    }

    public function hideAction()
    {
        $user = $this->apiIdentity();
        $this->service->hide($this->params('id'), $user);

        return [];
    }
}
