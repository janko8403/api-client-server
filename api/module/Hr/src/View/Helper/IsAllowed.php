<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.02.18
 * Time: 14:35
 */

namespace Hr\View\Helper;


use Rollbar\Rollbar;
use Hr\Acl\AclService;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;

class IsAllowed
{
    /**
     * @var AclService
     */
    private $acl;

    private $positionId;

    public function __construct(AclService $aclService, $positionId)
    {
        $this->acl = $aclService;
        $this->positionId = $positionId;
    }

    public function __invoke($resourceName)
    {
        try {
            return $this->acl->getAcl()->isAllowed($this->positionId, $resourceName);
        } catch (InvalidArgumentException $e) {
            Rollbar::warning($e);
        }

        return false;
    }
}