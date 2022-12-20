<?php


namespace Application\Authorization;


use Laminas\ApiTools\MvcAuth\Authorization\AclAuthorization;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Laminas\Authentication\AuthenticationService;

class AuthorizationListener
{
    public function __invoke(MvcAuthEvent $event)
    {
        // @doc https://api-tools.getlaminas.org/documentation/auth/user-differentiation

        /** @var AclAuthorization $authorization */
        $authorization = $event->getAuthorizationService();

        /** @var AuthenticationService $authentication */
//        $authentication = $event->getAuthenticationService();
//        $identity = $authentication->getIdentity();

        $authorization->allow();
    }
}