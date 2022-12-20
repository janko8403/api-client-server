<?php


namespace Hr\View\Helper;


use NumberFormatter;
use Laminas\View\Helper\AbstractHelper;

class Currency extends AbstractHelper
{
    public function __invoke($amount, $decimal = 2)
    {
        $numberFormat = $this->getView()->getHelperPluginManager()->get('numberFormat');

        return $numberFormat(
            $amount,
            NumberFormatter::DECIMAL,
            NumberFormatter::TYPE_DEFAULT,
            'pl_PL',
            $decimal
        );
    }

}