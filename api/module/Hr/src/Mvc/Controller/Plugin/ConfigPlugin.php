<?php


namespace Hr\Mvc\Controller\Plugin;


use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class ConfigPlugin extends AbstractPlugin
{
    /**
     * @var array
     */
    private $config;

    /**
     * ConfigPlugin constructor.
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