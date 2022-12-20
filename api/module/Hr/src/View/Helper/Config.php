<?php

namespace Hr\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __invoke()
    {
        return $this->config;
    }
}