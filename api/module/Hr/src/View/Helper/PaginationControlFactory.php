<?php
/**
 * Created by PhpStorm.
 * User: pawelz
 * Date: 2019-02-06
 * Time: 13:57
 */

namespace Hr\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PaginationControlFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PaginationControl(
            $container->get(\Mobile_Detect::class)
        );
    }
}