<?php

namespace ApiAssemblyOrders\V1\Rpc\Hide;

use ApiAssemblyOrders\V1\Service\HideService;

class HideControllerFactory
{
    public function __invoke($controllers)
    {
        return new HideController($controllers->get(HideService::class));
    }
}
