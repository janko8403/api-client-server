<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 05.06.17
 * Time: 10:36
 */

namespace Hr\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;

class Path extends AbstractHelper
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __invoke($pathKey)
    {
        return $this->config[$pathKey];
    }
}