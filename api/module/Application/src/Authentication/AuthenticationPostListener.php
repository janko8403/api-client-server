<?php


namespace Application\Authentication;


use Doctrine\Persistence\ObjectManager;
use Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity as MvcAuthAuthenticatedIdentity;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Users\Entity\User;

class AuthenticationPostListener
{
    private ObjectManager $objectManager;

    /**
     * AuthenticationPostListener constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        $identity = $mvcAuthEvent->getAuthenticationService()->getIdentity();
        if (!$identity instanceof MvcAuthAuthenticatedIdentity) {
            return;
        }

        $authenticatedIdentity = new AuthenticatedIdentity(
            $identity->getAuthenticationIdentity(),
            $mvcAuthEvent->getAuthorizationService(),
            $identity->getName()
        );

        $login = $authenticatedIdentity->getIdentity()['user_id'];
        $user = $this->objectManager->getRepository(User::class)->findOneBy(['login' => $login]);
        if ($user) {
            $authenticatedIdentity->setUser($user);

            $mvcAuthEvent->getMvcEvent()->setParam('Laminas\ApiTools\MvcAuth\Identity', $authenticatedIdentity);
            $mvcAuthEvent->setIdentity($authenticatedIdentity);
        }
    }
}