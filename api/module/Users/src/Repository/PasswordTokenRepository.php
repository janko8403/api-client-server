<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.04.18
 * Time: 09:24
 */

namespace Users\Repository;

use Doctrine\ORM\EntityRepository;

class PasswordTokenRepository extends EntityRepository
{
    public function getLastCodeForUser(int $userId): array
    {
        $date = new \DateTime();
        $date->modify('-30 minutes');
        $qb = $this->createQueryBuilder('pt');
        $qb->andWhere('pt.user = :userId')->setParameter('userId', $userId);
        $qb->andWhere('pt.generationDate > :date')->setParameter('date', $date);
        $qb->andWhere('pt.isactive = 1');
        $qb->setMaxResults(1);
        $qb->orderBy('pt.id', 'DESC');

        return $qb->getQuery()->execute();
    }

    public function getCodesFromLastNMinutes(int $userId, int $minutes): array
    {
        $date = new \DateTime();
        $date->modify("-$minutes minutes");
        $qb = $this->createQueryBuilder('pt');
        $qb->andWhere('pt.user = :userId')->setParameter('userId', $userId);
        $qb->andWhere('pt.generationDate > :date')->setParameter('date', $date);

        return $qb->getQuery()->execute();
    }

    public function findActiveCodeForPhoneNumber(string $phoneNumber, string $code, int $ageInMinutes): array
    {
        $date = new \DateTime();
        $date->modify("-$ageInMinutes minutes");
        $qb = $this->createQueryBuilder('pt');
        $qb->join('pt.user', 'u');
        $qb->andWhere('pt.code = :code')->setParameter('code', $code);
        $qb->andWhere('pt.generationDate > :date')->setParameter('date', $date);
        $qb->andWhere('u.phonenumber = :phoneNumber')->setParameter('phoneNumber', $phoneNumber);
        $qb->andWhere('pt.isactive = 1');

        return $qb->getQuery()->execute();
    }
}