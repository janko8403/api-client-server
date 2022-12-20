<?php

namespace ApiAssemblyOrders\V1\Rpc\FetchAll;

use ApiAssemblyOrders\V1\Service\AssemblyOrderService;
use Laminas\ApiTools\ContentNegotiation\JsonModel;
use Laminas\Mvc\Controller\AbstractActionController;

class FetchAllController extends AbstractActionController
{
    public function __construct(private AssemblyOrderService $service)
    {
    }

    public function fetchAllAction()
    {
        $user = $this->apiIdentity();
        $status = $this->params('status');
        $orders = $this->service->fetchAllForUser($user, $status, $this->params()->fromQuery('filters') ?? '');

        return new JsonModel(array_map(fn($o) => $o->toArray($user), $orders));
    }
}
