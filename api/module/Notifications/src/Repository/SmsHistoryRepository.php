<?php


namespace Notifications\Repository;


use Doctrine\ORM\Query;
use Hr\Repository\BaseRepository;
use Hr\Repository\SearchableInterface;

class SmsHistoryRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('h');
        $qb->addOrderBy('h.sendDate', 'desc');

        empty($data['name']) ?: $qb->andWhere("h.name LIKE :name")
            ->setParameter('name', "%$data[name]%");

        return $this->addOrder($qb, $data);
    }
}