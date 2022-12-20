<?php

namespace ApiNpsRatings\V1\Rpc\FetchAll;

use ApiNpsRatings\V1\Service\NpsRatingService;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Laminas\ApiTools\ContentNegotiation\JsonModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator as LaminasPaginator;

class FetchAllController extends AbstractActionController
{
    public function __construct(private NpsRatingService $service)
    {
    }

    public function fetchAllAction()
    {
        $user = $this->apiIdentity();
        $params = $this->params()->fromQuery();
        $page = $params['page'] ?? 1;
        $ratingsQuery = $this->service->fetchAllForUser($user);

        $paginator = new LaminasPaginator(new PaginatorAdapter(new ORMPaginator($ratingsQuery)));
        $paginator->setItemCountPerPage($params['perPage'] ?? 5)->setCurrentPageNumber($page);

        return new JsonModel([
            'data' => array_map(fn($r) => $r->toArray(), $paginator->getCurrentItems()->getArrayCopy()),
            'nextPage' => $paginator->getCurrentPageNumber() < $paginator->count() ? $page + 1 : null,
        ]);
    }
}
