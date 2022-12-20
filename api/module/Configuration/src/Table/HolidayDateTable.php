<?php

namespace Configuration\Table;

use Configuration\Entity\HolidayDate;
use Hr\Table\TableService;

class HolidayDateTable extends TableService
{
    /**
     * @throws \Exception
     */
    public function init()
    {
        $this->setId('holidayDateTable');
        $this->setOption(TableService::OPT_SHOW_EDIT, false);
        $this->configureColumns([
            'date' => [
                'label' => 'Data',
                'value' => 'getDate',
            ],
        ]);
        $this->addRowAction(function (HolidayDate $holidayDate): array {
            return [
                'title' => 'Usuń',
                'icon' => 'ban',
                'route' => 'configuration/holidayDates/remove',
                'route-params' => ['action' => 'remove', 'id' => $holidayDate->getId()],
                'class' => 'fa-lg a-holiday-date-remove',
            ];
        });
    }

    public function getDate(HolidayDate $holidayDate): string
    {
        return $holidayDate->getDate()?->format('Y-m-d') ?? '&ndash;';
    }
}