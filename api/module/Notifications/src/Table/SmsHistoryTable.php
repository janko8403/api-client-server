<?php

namespace Notifications\Table;

use Hr\Table\TableService;
use Hr\View\Helper\DateTime;

class SmsHistoryTable extends TableService
{
    public function init()
    {
        $this->setId('smsHistoryTable');
        $this->configureColumns([
            'name' => [
                'label' => 'Odbiorca',
            ],
            'number' => [
                'label' => 'Telefon',
            ],
            'sendDate' => [
                'label' => 'Data dodania',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME]],
            ],
            'text' => [
                'label' => 'Treść',
            ],
            'responseDate' => [
                'label' => 'Data odpowiedzi',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME]],
            ],
            'responseText' => [
                'label' => 'Odpowiedź',
            ],
        ]);
    }
}