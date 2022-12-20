<?php

namespace AssemblyOrders\Service;

use AssemblyOrders\Entity\AssemblyOrder;
use AssemblyOrders\Entity\AssemblyOrderUser;
use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;

class UserService
{
    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function getOrCreateUser(int $id, User $user): AssemblyOrderUser
    {
        $orderUser = $this->objectManager->getRepository(AssemblyOrderUser::class)->findOneBy(['user' => $user, 'order' => $id]);

        if (!$orderUser) {
            $orderUser = new AssemblyOrderUser();
            $orderUser->setUser($user);
            $orderUser->setOrder($this->objectManager->find(AssemblyOrder::class, $id));
        }

        return $orderUser;
    }
}