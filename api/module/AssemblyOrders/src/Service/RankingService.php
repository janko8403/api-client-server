<?php

namespace AssemblyOrders\Service;

use AssemblyOrders\Entity\AssemblyOrder;
use AssemblyOrders\Entity\Ranking;
use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;

class RankingService
{
    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function getRankingForOrder(int $orderId): array
    {
        return $this->objectManager->getRepository(Ranking::class)
            ->findBy(['order' => $orderId], orderBy: ['position' => 'asc']);
    }

    public function add(int $orderId, mixed $userId)
    {
        $position = $this->objectManager->getRepository(Ranking::class)->countForOrder($orderId);
        $ranking = new Ranking();
        $ranking->setUser($this->objectManager->find(User::class, $userId));
        $ranking->setOrder($this->objectManager->find(AssemblyOrder::class, $orderId));
        $ranking->setPosition($position + 1);
        $this->objectManager->persist($ranking);
        $this->objectManager->flush();
    }

    public function delete(int $id): void
    {
        $ranking = $this->objectManager->find(Ranking::class, $id);
        if ($ranking) {
            $this->objectManager->remove($ranking);
            $this->objectManager->flush();
        }
    }

    public function move(int $id, int $to): Ranking
    {
        $ranking = $this->objectManager->find(Ranking::class, $id);

        // find ranking at target position
        $target = $this->objectManager->getRepository(Ranking::class)->findOneBy(['order' => $ranking->getOrder(), 'position' => $to]);
        if ($target) {
            $target->setPosition($ranking->getPosition());
            $ranking->setPosition($to);

            $this->objectManager->persist($target);
            $this->objectManager->persist($ranking);
            $this->objectManager->flush();
        }

        return $ranking;
    }
}