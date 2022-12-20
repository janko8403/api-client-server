<?php

namespace Configuration\Repository;

use Doctrine\ORM\Query;
use Hr\Repository\BaseRepository;
use Hr\Repository\SearchableInterface;

class HolidayDateRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('hd');
        $qb->addOrderBy('hd.date', 'asc');
        return $qb->getQuery();
    }
}