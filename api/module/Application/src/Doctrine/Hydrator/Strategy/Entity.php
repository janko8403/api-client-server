<?php


namespace Application\Doctrine\Hydrator\Strategy;


use Laminas\Hydrator\Strategy\StrategyInterface;

class Entity implements StrategyInterface
{
    public function extract($value)
    {
        return $value;
    }

    public function hydrate($value)
    {
        die('not implemented');
        // TODO: Implement hydrate() method.
    }

}