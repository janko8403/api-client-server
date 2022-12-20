<?php
namespace ApiUsers\V1\Rpc\ResetPassword;

use ApiUsers\V1\Service\PasswordService;

class ResetPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        return new ResetPasswordController($controllers->get(PasswordService::class));
    }
}
