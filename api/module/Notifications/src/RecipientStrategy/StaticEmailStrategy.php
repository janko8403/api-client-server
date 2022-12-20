<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 19:15
 */

namespace Notifications\RecipientStrategy;

class StaticEmailStrategy implements StrategyInterface
{
    public function get(string $staticParams = null, array $runtimeParams = [])
    {
        return $staticParams;
    }
}