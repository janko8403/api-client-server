<?php

namespace ApiNpsRatings\V1\Rpc\Fetch;

use ApiNpsRatings\V1\Service\NpsRatingService;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\ApiTools\ContentNegotiation\JsonModel;
use Laminas\Mvc\Controller\AbstractActionController;

class FetchController extends AbstractActionController
{
    public function __construct(private NpsRatingService $service)
    {
    }

    public function fetchAction()
    {
        $id = $this->params('id');
        $user = $this->apiIdentity();
        $rating = $this->service->fetchForUser($id, $user);

        if (!$rating) {
            return new ApiProblemResponse(new ApiProblem(404, 'Rating not found'));
        }

        return new JsonModel($rating->toArray());
    }
}
