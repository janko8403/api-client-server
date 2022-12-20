<?php

namespace DocumentTemplates\Repository;

use Doctrine\ORM\Query;
use Hr\Repository\BaseRepository;
use Hr\Repository\SearchableInterface;

/**
 * DocumentTemplateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentTemplateRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('t');

        empty($data['name']) ?: $qb->andWhere('t.name LIKE :name')
            ->setParameter('name', "%$data[name]%");

        if (isset($data['isActive']) && $data['isActive'] != '') {
            $qb->andWhere($qb->expr()->eq('t.isActive', $data['isActive']));
        }

//        $order = $data['order'] ?? 'asc';
//        if (!empty($data['sort'])) {
//            $qb->orderBy($data['sort'], $order);
//        }

        return $this->addOrder($qb, $data);
    }
}