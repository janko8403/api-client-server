<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DateTime extends AbstractHelper
{
    const DATETIME = 'Y-m-d H:i:s';
    const DATETIME_SHORT = 'Y-m-d H:i';
    const DATE = 'Y-m-d';
    const TIME = 'H:i';

    public function __invoke($dt, $format = self::DATETIME)
    {
        if (empty($dt)) {
            return '-';
        } elseif (is_object($dt) && $dt instanceof \DateTime) {
            return $dt->format($format);
        } else {
            return $dt;
        }
    }
}