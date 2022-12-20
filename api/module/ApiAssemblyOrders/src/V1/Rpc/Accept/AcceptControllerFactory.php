<?php

namespace ApiAssemblyOrders\V1\Rpc\Accept;

use ApiAssemblyOrders\V1\Service\AcceptService;

class AcceptControllerFactory
{
    public function __invoke($controllers)
    {
        return new AcceptController($controllers->get(AcceptService::class));
    }
}
