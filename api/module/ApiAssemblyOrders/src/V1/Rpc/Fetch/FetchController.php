<?php

namespace ApiAssemblyOrders\V1\Rpc\Fetch;

use ApiAssemblyOrders\V1\Service\AssemblyOrderService;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\ApiTools\ContentNegotiation\JsonModel;
use Laminas\Mvc\Controller\AbstractActionController;

class FetchController extends AbstractActionController
{
    public function __construct(private AssemblyOrderService $service)
    {
    }

    public function fetchAction()
    {
        $id = $this->params('id');
        $user = $this->apiIdentity();
        $order = $this->service->fetchForUser($id, $user);

        if (!$order) {
            return new ApiProblemResponse(new ApiProblem(404, 'Order not found'));
        }

        return new JsonModel($order->toArray($user));
    }
}
