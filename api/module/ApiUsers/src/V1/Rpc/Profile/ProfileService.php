<?php

namespace ApiUsers\V1\Rpc\Profile;

use Users\Entity\User;

class ProfileService
{

    public function getProfile(User $user): array
    {
        return [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'firstName' => $user->getName(),
            'lastName' => $user->getSurname(),
            'email' => $user->getEmail(),
        ];
    }
}