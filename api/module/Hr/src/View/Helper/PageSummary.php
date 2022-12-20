<?php

namespace Hr\View\Helper;

use Laminas\Paginator\Paginator;
use Laminas\View\Helper\AbstractHelper;

class PageSummary extends AbstractHelper
{
    public function __invoke(Paginator $paginator)
    {
        $translateHelper = $this->getView()->getHelperPluginManager()->get('translate');

        return sprintf(
            '%s: <b>%d</b> %s <b>%d</b>',
            $translateHelper('Wyświetlono rekordów'),
            $paginator->getCurrentItemCount(),
            'z',
            $paginator->getTotalItemCount()
        );
    }
}