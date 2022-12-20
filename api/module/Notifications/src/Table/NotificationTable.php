<?php

namespace Notifications\Table;

use Hr\Table\TableService;
use Notifications\Entity\Notification;

class NotificationTable extends TableService
{
    public function init()
    {
        $this->setId('notificationTable');
        $this->configureColumns([
            'transport' => [
                'label' => 'Transport',
                'value' => 'getTransport',
            ],
            'type' => [
                'label' => 'Typ',
                'value' => 'getTypeName',
            ],
            'subject' => [
                'label' => 'Temat',
            ],
        ]);
    }

    public function getTypeName(Notification $notification)
    {
        return $notification->getTypeName();
    }

    public function getTransport(Notification $notification)
    {
        return $notification->getTransport() == '1' ? 'email' : 'SMS';
    }
}