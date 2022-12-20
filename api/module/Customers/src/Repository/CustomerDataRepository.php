<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 11.09.2018
 * Time: 14:57
 */

namespace Customers\Repository;

use Hr\Repository\BaseRepository;

class CustomerDataRepository extends BaseRepository
{
    public function updateField(int $customerId, string $field, string $value)
    {
        $sql = sprintf(
            "UPDATE customerData SET %s = :value WHERE customerId = :id AND isActive = 1",
            $field
        );
        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->execute(['value' => $value, 'id' => $customerId]);
    }
}