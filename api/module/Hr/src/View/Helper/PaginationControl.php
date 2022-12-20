<?php
/**
 * Created by PhpStorm.
 * User: pawelz
 * Date: 2019-02-06
 * Time: 13:56
 */

namespace Hr\View\Helper;

use Laminas\Paginator;

class PaginationControl extends \Laminas\View\Helper\PaginationControl
{
    /**
     * @var \Mobile_Detect
     */
    private $mobileDetect;

    /**
     * PaginationControl constructor.
     *
     * @param \Mobile_Detect $mobileDetect
     */
    public function __construct(\Mobile_Detect $mobileDetect)
    {
        $this->mobileDetect = $mobileDetect;
    }

    public function __invoke(Paginator\Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
    {
        $maxPages = $this->mobileDetect->isMobile() ? 5 : 10;
        $paginator->setPageRange($maxPages);

        return parent::__invoke($paginator, $scrollingStyle, $partial, $params);
    }
}