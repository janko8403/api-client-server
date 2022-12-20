<?php

namespace AssemblyOrders\Repository;

use Hr\Repository\BaseRepository;

class RankingRepository extends BaseRepository
{
    public function countForOrder(int $orderId): int
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('count(r)');
        $qb->andWhere('r.order = :order')->setParameter('order', $orderId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}