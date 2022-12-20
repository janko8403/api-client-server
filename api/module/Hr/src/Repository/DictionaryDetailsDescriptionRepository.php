<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 20.09.17
 * Time: 11:39
 */

namespace Hr\Repository;


class DictionaryDetailsDescriptionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getDesctiptionForDetails($id)
    {
        $qb = $this->createQueryBuilder('ddd');
        $qb->andWhere($qb->expr()->eq('ddd.dictionaryDetail', $id));

        $tmp = [];
        $data = $qb->getQuery()->execute();
        foreach ($data as $d) {
            $tmp[$d->getKey()] = $d->getName();
        }

        return $tmp;
    }
}