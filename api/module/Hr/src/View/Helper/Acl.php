<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 29.08.17
 * Time: 13:42
 */

namespace Hr\View\Helper;


use Hr\Acl\AclService;

class Acl
{
    /**
     * @var AclService
     */
    private $acl;

    public function __construct(AclService $acl)
    {
        $this->acl = $acl;
    }

    public function __invoke()
    {
        return $this->acl;
    }
}