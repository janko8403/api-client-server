<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 11.01.18
 * Time: 18:10
 */

namespace Hr\View\Helper;


use Users\Entity\User;
use Laminas\View\Helper\AbstractHelper;

class IsAdmin extends AbstractHelper
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke($type = null)
    {
        if ($type) {
            return $this->user->isAdminType($type);
        }

        return $this->user->isAdmin();
    }

}