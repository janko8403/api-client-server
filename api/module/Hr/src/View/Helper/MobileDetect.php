<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class MobileDetect extends AbstractHelper
{
    public function __invoke()
    {
        return new \Mobile_Detect();
    }

}