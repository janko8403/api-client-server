<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 25.01.18
 * Time: 09:53
 */

namespace Notifications\RecipientStrategy;


use Users\Entity\User;

class SupervisorStrategy implements StrategyInterface
{
    public function get(string $staticParams = null, array $runtimeParams = [])
    {
        /** @var User $user */
        $user = $runtimeParams['user'];
        if (!$user->getSupervisor()){
            return '';
        }
        /** @var  User $supervisor */
        $supervisor = $user->getSupervisor();
        if ($supervisor) {
            $data = $supervisor->getEmail();
            $validator = new \Laminas\Validator\EmailAddress();
            if ($data && $validator->isValid($data)) {
                return $data;
            }
        }

        return '';
    }
}