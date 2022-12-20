<?php

namespace ApiAssemblyOrders\V1\Rpc\FetchAll;

use ApiAssemblyOrders\V1\Service\AssemblyOrderService;

class FetchAllControllerFactory
{
    public function __invoke($controllers)
    {
        return new FetchAllController($controllers->get(AssemblyOrderService::class));
    }
}
