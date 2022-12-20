<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.11.2017
 * Time: 18:50
 */

namespace Hr\View\Helper;

class HeadScript extends \Laminas\View\Helper\HeadScript
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

    public function itemToString($item, $indent, $escapeStart, $escapeEnd)
    {
        if (isset($item->attributes['src']) && !strstr($item->attributes['src'], '?')) {
            $item->attributes['src'] = $item->attributes['src'] . '?v=' . $this->version;
        }

        return parent::itemToString($item, $indent, $escapeStart, $escapeEnd);
    }
}