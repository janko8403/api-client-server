<?php

namespace ApiNpsRatings\V1\Rpc\Fetch;

use ApiNpsRatings\V1\Service\NpsRatingService;

class FetchControllerFactory
{
    public function __invoke($controllers)
    {
        return new FetchController($controllers->get(NpsRatingService::class));
    }
}
