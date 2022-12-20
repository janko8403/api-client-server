<?php

namespace ApiAssemblyOrders\V1\Service;

use AssemblyOrders\Entity\AssemblyOrderUser;
use AssemblyOrders\Service\UserService;
use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;

class HideService
{
    public function __construct(private ObjectManager $objectManager, private UserService $userService)
    {
    }

    public function hide(int $id, User $user): AssemblyOrderUser
    {
        $orderUser = $this->userService->getOrCreateUser($id, $user);
        $orderUser->setStatus(AssemblyOrderUser::STATUS_HIDDEN);
        $this->objectManager->persist($orderUser);
        $this->objectManager->flush();

        return $orderUser;
    }
}