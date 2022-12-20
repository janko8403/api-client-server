<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 23.12.2016
 * Time: 12:22
 */

namespace Hr\Repository;

use Doctrine\ORM\Query;

interface SearchableInterface
{
    public function search(array $data): Query;
}