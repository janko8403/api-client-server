<?php


namespace Application\Authentication;

use Users\Entity\User;
use Laminas\ApiTools\MvcAuth\Identity\IdentityInterface;
use Laminas\ApiTools\MvcAuth\Authorization\AuthorizationInterface;
use Laminas\Permissions\Rbac\Role as RbacRole;
use Laminas\Permissions\Acl\Acl;

class AuthenticatedIdentity extends RbacRole implements IdentityInterface
{
    protected $identity;
    protected $authorizationService;
    protected $name;

    /**
     * @var User
     */
    protected $user;

    public function __construct($identity, AuthorizationInterface $authorizationService, $name)
    {
        $this->identity = $identity;
        $this->authorizationService = $authorizationService;
        $this->name = $name;
    }

    public function getAuthenticationIdentity()
    {
        return [
            'user' => $this->getUser(),
            'identity' => $this->identity,
        ];
    }

    public function getAuthorizationService()
    {
        return $this->authorizationService;
    }

    public function isAuthorized($resource, $privilege)
    {
        if ($this->authorizationService instanceof Acl) {
            return $this->authorizationService->isAuthorized($this, $resource, $privilege);
        } else {
            throw new \Exception('isAuthorized is for ACL only.');
        }
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return AuthenticatedIdentity
     */
    public function setUser(User $user): AuthenticatedIdentity
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param mixed $identity
     * @return AuthenticatedIdentity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function getRoleId()
    {
        return $this->getUser()->getConfigurationPosition()->getId();
    }
}