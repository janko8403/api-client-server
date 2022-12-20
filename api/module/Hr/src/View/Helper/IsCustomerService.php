<?php

namespace Hr\View\Helper;


use Laminas\View\Helper\AbstractHelper;
use Users\Entity\User;

class IsCustomerService extends AbstractHelper
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke()
    {
        return $this->user->isCustomerService();
    }

}