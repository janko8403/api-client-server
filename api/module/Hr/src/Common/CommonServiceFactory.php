<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.11.2016
 * Time: 11:17
 */

namespace Hr\Common;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CommonServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        var_dump($requestedName);
        die();
    }

}