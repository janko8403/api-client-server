<?php

namespace NpsRating\Repository;

use Doctrine\ORM\Query;
use Hr\Repository\BaseRepository;
use NpsRating\Entity\NpsRating;
use Users\Entity\User;

class NpsRatingRepository extends BaseRepository
{
    public function fetchAllForUser(User $user): Query
    {
        $qb = $this->createQueryBuilder('n')
            ->where('n.user = :user')->setParameter('user', $user)
            ->addOrderBy('n.date', 'DESC');

        return $qb->getQuery();
    }

    public function fetchForUser(int $id, User $user): ?NpsRating
    {
        return $this->findOneBy(['id' => $id, 'user' => $user]);
    }

    public function deleteAllNpsRating()
    {
        $qb = $this->createQueryBuilder('n');
        $qb->delete();
        return $qb->getQuery()->execute();
    }
}