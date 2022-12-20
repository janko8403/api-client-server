<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class Checkbox extends AbstractHelper
{
    public function __invoke($value)
    {
        $html = '<i class="fa-regular fa-%s %s" aria-hidden="true"></i>';

        $icon = !empty($value) ? 'square-check' : 'square';
        $color = !empty($value) ? 'text-success' : 'text-danger';

        return sprintf($html, $icon, $color);
    }
}