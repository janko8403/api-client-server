<?php

namespace ApiAssemblyOrders\V1\Rpc\Accept;

use ApiAssemblyOrders\V1\Service\AcceptService;
use AssemblyOrders\Module;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\Mvc\Controller\AbstractActionController;

class AcceptController extends AbstractActionController
{
    public function __construct(private AcceptService $service)
    {
    }

    public function acceptAction()
    {
        $id = $this->params('id');
        $user = $this->apiIdentity();

        if ($this->service->isAvailable($id)) {
            $orderUser = $this->service->accept($id, $user);

            $this->getEventManager()->trigger(Module::ASSEMBLY_ORDER_ACCEPTED, $orderUser);
        } else {
            return new ApiProblemResponse(new ApiProblem(400, 'Zlecenie jest niedostępne'));
        }

        return [];
    }
}
