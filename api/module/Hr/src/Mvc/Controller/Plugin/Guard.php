<?php


namespace Hr\Mvc\Controller\Plugin;


use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Stdlib\ResponseInterface;
use Hr\Acl\AclService;

class Guard extends AbstractPlugin
{
    private AclService $service;

    /**
     * Guard constructor.
     *
     * @param AclService $service
     */
    public function __construct(AclService $service)
    {
        $this->service = $service;
    }

    public function __invoke(string $resource): ?ResponseInterface
    {
        $identity = $this->getController()->apiIdentity();
        $allowed = $this->service->getAcl()->isAllowed($identity->getConfigurationPosition()->getId(), $resource);

        if (!$allowed) {
            return new ApiProblemResponse(new ApiProblem(403, 'Not allowed'));
        }

        return null;
    }

}