<?php

namespace Hr\Config;

trait ConfigAwareTrait
{
    protected array $config;

    public function setConfig(array $config)
    {
        $this->config = $config;
    }
}