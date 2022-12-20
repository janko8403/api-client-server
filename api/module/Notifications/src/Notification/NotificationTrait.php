<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 11.11.2017
 * Time: 17:46
 */

namespace Notifications\Notification;

use Doctrine\Persistence\ObjectManager;
use Notifications\RecipientStrategy\RecipientFactory;
use Notifications\Transport\TransportFactory;

trait NotificationTrait
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var RecipientFactory
     */
    protected $recipientFactory;

    /**
     * @var TransportFactory
     */
    protected $transportFactory;

    public function __construct(
        ObjectManager    $objectManager,
        RecipientFactory $recipientFactory,
        TransportFactory $transportFactory
    )
    {
        $this->objectManager = $objectManager;
        $this->recipientFactory = $recipientFactory;
        $this->transportFactory = $transportFactory;
    }
}