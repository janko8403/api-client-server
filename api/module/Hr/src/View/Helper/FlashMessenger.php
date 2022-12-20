<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class FlashMessenger extends AbstractHelper
{
    public function __invoke()
    {
        $fm = $this->view->flashMessenger();
        $fm->setMessageOpenFormat('<div%s>
			<ul class="mb-0"><li>')
            ->setMessageSeparatorString('</li><li>')
            ->setMessageCloseString('</li></ul></div>')
            ->setAutoEscape(false);

        $html = '';
        $html .= $fm->render('error', ['alert', 'alert-dismissable', 'alert-danger']);
        $html .= $fm->render('info', ['alert', 'alert-dismissable', 'alert-info']);
        $html .= $fm->render('warning', ['alert', 'alert-dismissable', 'alert-warning']);
        $html .= $fm->render('success', ['alert', 'alert-dismissable', 'alert-success']);

        return $html;
    }
}