<?php

namespace Hr\View\Helper;

use Laminas\Router\Http\RouteMatch;
use Laminas\View\Helper\AbstractHelper;

class UrlParam extends AbstractHelper
{
    /**
     * @var RouteMatch
     */
    private $routeMatch;

    /**
     * @var array
     */
    private $queryParams;

    /**
     * UrlParam constructor.
     *
     * @param RouteMatch $routeMatch
     * @param array      $queryParams
     */
    public function __construct(RouteMatch $routeMatch, array $queryParams)
    {
        $this->routeMatch = $routeMatch;
        $this->queryParams = $queryParams;
    }

    public function __invoke($param, $default = null)
    {
        if (!empty($this->routeMatch)) {
            $value = $this->routeMatch->getParam($param);
        }

        if (!isset($value)) {
            // check if GET parameter doesn't exist
            $value = $this->queryParams[$param] ?? $default;
        }

        return isset($value) ? $value : $default;
    }
}