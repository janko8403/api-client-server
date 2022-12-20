<?php

namespace AssemblyOrders\Notification;

use AssemblyOrders\Entity\AssemblyOrderUser;
use Doctrine\Persistence\ObjectManager;
use Notifications\Entity\Notification;
use Notifications\Sms\SmsService;

class OrderAcceptedSMS
{
    public function __construct(private ObjectManager $objectManager, private SmsService $smsService)
    {
    }

    public function send(AssemblyOrderUser $orderUser): void
    {
        $order = $orderUser->getOrder();
        $notification = $this->objectManager->getRepository(Notification::class)->findOneBy(['type' => Notification::TYPE_SMS_ORDER_ACCEPTED]);
        $message = str_replace(
            [
                '[nr_zlecenia]',
                '[imię i nazwisko]',
                '[adres]',
            ],
            [
                $order->getIdInstallationOrder(), // nr_zlecenia
                $order->getInstallationName(), // imię i nazwisko
                $orderUser->getOrder()->getInstallationCity() . ', ' . $order->getInstallationAddress(), // adres
            ],
            $notification->getContent()
        );

        $this->smsService->send($orderUser->getUser()->getPhonenumber(), $message);
    }
}