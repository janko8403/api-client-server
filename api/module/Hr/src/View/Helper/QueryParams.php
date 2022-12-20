<?php

namespace Hr\View\Helper;

use Laminas\Router\Http\RouteMatch;
use Laminas\View\Helper\AbstractHelper;

class QueryParams extends AbstractHelper
{
    /**
     * @var array
     */
    private $queryParams;

    /**
     * QueryParams constructor.
     *
     * @param array $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    public function __invoke()
    {
        return $this->queryParams ?? [];
    }
}