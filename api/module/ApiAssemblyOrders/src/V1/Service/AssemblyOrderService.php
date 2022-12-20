<?php

namespace ApiAssemblyOrders\V1\Service;

use AssemblyOrders\Entity\AssemblyOrder;
use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;

class AssemblyOrderService
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_ACCEPTED = 'accepted';

    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function fetchAllForUser(User $user, string $status, string $filters): array
    {
        $filters = array_flip(explode(',', $filters));

        return $this->objectManager->getRepository(AssemblyOrder::class)->fetchAllForUser($user, $status, $filters);
    }

    public function fetchForUser(int $id, $user): ?AssemblyOrder
    {
        return $this->objectManager->getRepository(AssemblyOrder::class)->fetchForUser($id, $user);
    }
}