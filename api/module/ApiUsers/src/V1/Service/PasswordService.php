<?php

namespace ApiUsers\V1\Service;

use Doctrine\Persistence\ObjectManager;
use Laminas\Crypt\Password\Bcrypt;
use Users\Entity\User;

class PasswordService
{
    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function fetchUserByEmail(string $email): ?User
    {
        return $this->objectManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function updatePasswordToken(User $user): void
    {
        $user->setPasswordToken(uniqid());
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }

    public function findUserByToken(string $token): ?User
    {
        return $this->objectManager->getRepository(User::class)->findOneBy(['passwordToken' => $token]);
    }

    public function setNewPassword(User $user, string $password): void
    {
        $bc = new Bcrypt();
        $password = $bc->create($password);
        $user->setPassword($password);
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
}