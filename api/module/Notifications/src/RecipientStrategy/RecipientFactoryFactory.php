<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 19:21
 */

namespace Notifications\RecipientStrategy;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RecipientFactoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return RecipientFactory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RecipientFactory($container);
    }
}