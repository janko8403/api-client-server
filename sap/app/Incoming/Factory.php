<?php

namespace App\Incoming;

class Factory
{
    public static function create(ActionEnum $action): ActionInterface
    {
        switch ($action) {
            case ActionEnum::CREATE_USER:
                return new CreateUser();
            case ActionEnum::UPDATE_USER:
                return new UpdateUser();
            case ActionEnum::CREATE_USER_STORE:
                return new CreateUserStore();
            case ActionEnum::DELETE_USER_STORE:
                return new DeleteUserStore();
            case ActionEnum::CREATE_STORE:
                return new CreateStore();
            case ActionEnum::UPDATE_STORE:
                return new UpdateStore();
            case ActionEnum::CREATE_ASSEMBLY_ORDER:
                return new CreateAssemblyOrder();
            case ActionEnum::UPDATE_ASSEMBLY_ORDER:
                return new UpdateAssemblyOrder();
            default:
                throw new \Exception('Action not found');
        }
    }
}
