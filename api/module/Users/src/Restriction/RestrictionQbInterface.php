<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 23.05.2018
 * Time: 15:12
 */

namespace Users\Restriction;

use Doctrine\ORM\QueryBuilder;

interface RestrictionQbInterface
{
    public function add(QueryBuilder $queryBuilder, array $params = []) : QueryBuilder;
}