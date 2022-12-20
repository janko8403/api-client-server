<?php


namespace Hr\Service;

use Interop\Container\ContainerInterface;

trait ContainerTrait
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ContainerTrait constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}