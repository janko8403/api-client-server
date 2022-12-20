<?php

namespace Hr\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;
use Hr\Authorization\AuthorizationService;

/**
 * Description of PermissionsHelper
 *
 * @author daniel
 */
class PermissionsHelper extends AbstractHelper
{
    /**
     *
     * @var type AuthorizationService
     */
    private $authorizationService;

    /**
     *
     * @param AuthorizationService $authorizationService
     */
    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     *
     * @param string $resourceName
     * @param string $mode
     * @return type bool
     */
    function __invoke(string $resourceName, string $mode)
    {
        return $this->authorizationService->isAllowed($resourceName, $mode);
    }
}
