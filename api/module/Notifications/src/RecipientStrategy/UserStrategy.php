<?php

namespace Notifications\RecipientStrategy;

use Users\Entity\User;

class UserStrategy implements StrategyInterface
{
    public function get(string $staticParams = null, array $runtimeParams = [])
    {
        /** @var User $user */
        $user = $runtimeParams['user'];
        $data = trim($user->getEmail());
        $validator = new \Laminas\Validator\EmailAddress();
        if ($data && $validator->isValid($data)) {
            return $data;
        }

        return '';
    }
}