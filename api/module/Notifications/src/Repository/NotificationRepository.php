<?php


namespace Notifications\Repository;


use Doctrine\ORM\Query;
use Notifications\Entity\Notification;
use Hr\Repository\BaseRepository;
use Hr\Repository\SearchableInterface;

class NotificationRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('n');
        $qb->join('n.instance', 'i');
        $qb->andWhere($qb->expr()->in('n.type', array_keys(Notification::TYPES)));
        $qb->addOrderBy('i.id', 'asc');
        $qb->addOrderBy('n.transport', 'asc');
        $qb->addOrderBy('n.type', 'asc');

        if (!empty($data['subject'])) {
            $qb->andWhere('n.subject LIKE :subject')->setParameter('subject', "%$data[subject]%");
        }
        if (!empty($data['type'])) {
            $qb->andWhere('n.type = :type')->setParameter('type', $data['type']);
        }

        return $qb->getQuery();
    }

}