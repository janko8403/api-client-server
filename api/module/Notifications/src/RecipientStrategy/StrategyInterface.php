<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 19:24
 */

namespace Notifications\RecipientStrategy;

interface StrategyInterface
{
    public function get(string $staticParams = null, array $runtimeParams = []);
}