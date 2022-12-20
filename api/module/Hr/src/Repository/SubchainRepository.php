<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 03:00
 */

namespace Hr\Repository;

use Doctrine\ORM\Query;

class SubchainRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->innerJoin('s.chain', 'ddc');

        empty($data['name']) ?: $qb->andWhere('s.name LIKE :name')->setParameter('name', "%$data[name]%");
        if (!empty($data['chain'])) {
            if (is_array($data['chain'])) {
                $qb->andWhere($qb->expr()->in('ddc.id', $data['chain']));
            } else {
                $qb->andWhere($qb->expr()->eq('ddc.id', $data['chain']));
            }
        }

        return $this->addOrder($qb, $data);
    }
}