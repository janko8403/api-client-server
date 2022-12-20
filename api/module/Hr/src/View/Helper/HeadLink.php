<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.11.2017
 * Time: 18:55
 */

namespace Hr\View\Helper;

use stdClass;

class HeadLink extends \Laminas\View\Helper\HeadLink
{
    /**
     * @var string
     */
    private $version;

    public function __construct(string $version)
    {
        $this->version = $version;
        parent::__construct();
    }

    public function itemToString(stdClass $item)
    {
        if (isset($item->href)) {
            $item->href = $item->href . '?v=' . $this->version;
        }

        return parent::itemToString($item);
    }
}