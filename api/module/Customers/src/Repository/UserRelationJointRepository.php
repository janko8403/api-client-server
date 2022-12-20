<?php


namespace Customers\Repository;

use Hr\Repository\BaseRepository;

class UserRelationJointRepository extends BaseRepository
{
    public function fetchUsersForCustomer(int $customerId): array
    {
        $qb = $this->createQueryBuilder('j');
        $qb->select('j', 'u', 'r');
        $qb->innerJoin('j.user', 'u');
        $qb->innerJoin('j.relation', 'r');
        $qb->andWhere('j.customer = :customer')->setParameter('customer', $customerId);

        return $qb->getQuery()->execute();
    }

    public function fetchCustomersForUser(int $userId): array
    {
        $qb = $this->createQueryBuilder('j');
        $qb->select('j', 'u', 'r');
        $qb->innerJoin('j.user', 'u');
        $qb->innerJoin('j.relation', 'r');
        $qb->andWhere('j.user = :user')->setParameter('user', $userId);

        return $qb->getQuery()->execute();
    }
}