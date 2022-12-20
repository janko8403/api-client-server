<?php

namespace Hr\View\Helper;

use Laminas\Paginator\Paginator;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\View\Helper\AbstractHelper;

class OrdinalNumber extends AbstractHelper
{
    public function __invoke(Paginator $paginator, $i)
    {
        return ($paginator->getCurrentPageNumber() - 1) * $paginator->getItemCountPerPage() + $i + 1;
    }
}