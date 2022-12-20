<?php

namespace ApiNpsRatings\V1\Rpc\FetchAll;

use ApiNpsRatings\V1\Service\NpsRatingService;

class FetchAllControllerFactory
{
    public function __invoke($controllers)
    {
        return new FetchAllController($controllers->get(NpsRatingService::class));
    }
}
