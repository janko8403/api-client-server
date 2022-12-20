<?php

namespace Hr\Repository;

use Hr\Entity\RecordPickerFilter;

/**
 * RecordPickerFilterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RecordPickerFilterRepository extends \Doctrine\ORM\EntityRepository
{
    public function findRecordsArray($userId, $filterId)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere($qb->expr()->eq('r.user', $userId));
        $qb->andWhere('r.filterId = :filterId')->setParameter('filterId', $filterId);

        $record = $qb->getQuery()->execute();

        if (!empty($record)) {
            return $record[0]->getRecords();
        }

        return [];
    }

    public function clearFilter(int $userId, string $filterId)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->delete(RecordPickerFilter::class, 'f');
        $qb->andWhere('f.filterId = :filterId')->setParameter('filterId', $filterId);
        $qb->andWhere('f.user = :user')->setParameter('user', $userId);
        $qb->getQuery()->execute();
    }
}