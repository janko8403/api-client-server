<?php

namespace ApiAssemblyOrders\V1\Rpc\Fetch;

use ApiAssemblyOrders\V1\Service\AssemblyOrderService;

class FetchControllerFactory
{
    public function __invoke($controllers)
    {
        return new FetchController($controllers->get(AssemblyOrderService::class));
    }
}
