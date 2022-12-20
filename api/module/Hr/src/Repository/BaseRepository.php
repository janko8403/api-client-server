<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 27.07.17
 * Time: 12:18
 */

namespace Hr\Repository;

class BaseRepository extends \Doctrine\ORM\EntityRepository
{
    protected function addOrder($qb, $data)
    {
        if (isset($data['sort']) && is_array($data['sort'])) {
            foreach ($data['sort'] as $k => $v) {
                if ($v != '' && $k != '') {
                    $qb->addOrderBy($k, $v);
                }
            }
        }

        return $qb->getQuery();
    }
}