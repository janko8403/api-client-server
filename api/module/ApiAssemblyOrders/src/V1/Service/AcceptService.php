<?php

namespace ApiAssemblyOrders\V1\Service;

use AssemblyOrders\Entity\AssemblyOrder;
use AssemblyOrders\Entity\AssemblyOrderUser;
use AssemblyOrders\Service\UserService;
use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;

class AcceptService
{
    public function __construct(private ObjectManager $objectManager, private UserService $userService)
    {
    }

    public function accept(int $id, User $user): AssemblyOrderUser
    {
        $orderUser = $this->userService->getOrCreateUser($id, $user);

        $orderUser->setStatus(AssemblyOrderUser::STATUS_ACCEPTED);
        $orderUser->getOrder()->setTaken(true);

        $this->objectManager->persist($orderUser);
        $this->objectManager->persist($orderUser->getOrder());
        $this->objectManager->flush();

        return $orderUser;
    }

    public function isAvailable(int $id): bool
    {
        $order = $this->objectManager->find(AssemblyOrder::class, $id);

        return !$order->isTaken();
    }
}