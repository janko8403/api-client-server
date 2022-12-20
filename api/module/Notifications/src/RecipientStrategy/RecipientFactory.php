<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 19:21
 */

namespace Notifications\RecipientStrategy;

use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;

class RecipientFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * RecipientFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function factory(string $strategyClass) : StrategyInterface
    {
        switch ($strategyClass) {
            case UserDataStrategy::class:
                $em = $this->container->get(ObjectManager::class);
                return new UserDataStrategy($em);
            default:
                return new $strategyClass();
        }
    }
}