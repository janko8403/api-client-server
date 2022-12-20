<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 11.11.2017
 * Time: 17:28
 */

namespace Notifications\Notification;

use Doctrine\Persistence\ObjectManager;
use Notifications\RecipientStrategy\RecipientFactory;
use Notifications\Transport\TransportFactory;

interface NotificationInterface
{
    public function __construct(
        ObjectManager $objectManager,
        RecipientFactory $recipientFactory,
        TransportFactory $transportFactory
    );

    public function send(array $params);
}