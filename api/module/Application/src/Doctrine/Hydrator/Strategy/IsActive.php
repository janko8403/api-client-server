<?php

namespace Application\Doctrine\Hydrator\Strategy;

use Doctrine\ORM\PersistentCollection;
use ZF\Doctrine\Hydrator\Strategy\CollectionExtract;


class IsActive extends CollectionExtract
{
    /**
     * @param PersistentCollection $value
     * @return mixed|void|\Laminas\ApiTools\Hal\Collection
     */
    public function extract($value)
    {
        $filtered = $value->filter(function($e) {
            return $e->getIsActive();
        });

        return parent::extract($filtered);
    }

}