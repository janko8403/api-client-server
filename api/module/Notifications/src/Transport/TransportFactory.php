<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2017
 * Time: 19:21
 */

namespace Notifications\Transport;

use Interop\Container\ContainerInterface;
use Notifications\Entity\Notification;
use Notifications\Mail\MailService;

class TransportFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * TransportFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function factory(string $type)
    {
        switch ($type) {
            case Notification::TRANSPORT_EMAIL:
                return $this->container->get(MailService::class);
        }
    }
}