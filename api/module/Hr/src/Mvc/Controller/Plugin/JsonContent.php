<?php


namespace Hr\Mvc\Controller\Plugin;

use Application\Authentication\AuthenticatedIdentity;
use Laminas\Json\Json;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class JsonContent extends AbstractPlugin
{
    public function __invoke()
    {
        $content = $this->getController()->getRequest()->getContent();

        return Json::decode($content, Json::TYPE_ARRAY);
    }
}