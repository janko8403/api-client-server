<?php

namespace ApiUsers\V1\Rpc\ChangePassword;

use ApiUsers\V1\Service\PasswordService;

class ChangePasswordControllerFactory
{
    public function __invoke($controllers)
    {
        return new ChangePasswordController($controllers->get(PasswordService::class));
    }
}
