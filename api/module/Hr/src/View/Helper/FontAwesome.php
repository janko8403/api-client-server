<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class FontAwesome extends AbstractHelper
{
    public function __invoke(string $icon, array $attrs = [])
    {
        $classes = $attrs['class'] ?? '';
        $style = $attrs['style'] ?? '';

        return "<em class='fa fa-{$icon} {$classes}' style='{$style}'></em>";
    }

}