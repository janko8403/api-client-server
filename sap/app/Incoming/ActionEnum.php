<?php

namespace App\Incoming;

enum ActionEnum:string
{
    case CREATE_USER = 'create_user';
    case UPDATE_USER = 'update_user';

    case CREATE_USER_STORE = 'create_user_store';
    case DELETE_USER_STORE = 'delete_user_store';

    case CREATE_STORE = 'create_store';
    case UPDATE_STORE = 'update_store';

    case CREATE_ASSEMBLY_ORDER = 'create_assembly_order';
    case UPDATE_ASSEMBLY_ORDER = 'update_assembly_order';
}
