<?php


namespace Hr\Mvc\Controller\Plugin;

use Application\Authentication\AuthenticatedIdentity;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class ApiIdentity extends AbstractPlugin
{
    public function __invoke()
    {
        $event = $this->getController()->getEvent();
        $identity = $event->getParam('Laminas\ApiTools\MvcAuth\Identity');

        if ($identity && $identity instanceof AuthenticatedIdentity) {
            return $identity->getUser();
        }

        return null;
    }
}