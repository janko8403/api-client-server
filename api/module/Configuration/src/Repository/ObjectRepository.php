<?php

namespace Configuration\Repository;

/**
 * ObjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObjectRepository extends \Doctrine\ORM\EntityRepository
{
    public function getFieldOrder(string $objectName, string $type)
    {
        $qb = $this->createQueryBuilder('o');
        $qb->leftJoin('o.fields', 'f');
        $qb->leftJoin('f.details', 'd');
        $qb->andWhere('o.name = :name')->setParameter('name', $objectName);
        $qb->andWhere('o.type = :type')->setParameter('type', $type);
        $qb->andWhere($qb->expr()->eq('o.isActive', true));
        $qb->orderBy('f.sequence');

        $data = $qb->getQuery()->execute();

        if (!empty($data)) {
            return $data[0];
        }

        return null;
    }
}