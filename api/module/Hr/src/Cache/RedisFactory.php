<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.2017
 * Time: 15:05
 */

namespace Hr\Cache;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RedisFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $redis = new \Redis();
        $redis->connect(\REDIS_HOST, \REDIS_PORT);
        $redis->ttl(7200);

        return $redis;
    }

}