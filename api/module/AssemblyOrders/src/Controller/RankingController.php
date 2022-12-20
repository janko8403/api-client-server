<?php

namespace AssemblyOrders\Controller;

use AssemblyOrders\Form\AddRankingForm;
use AssemblyOrders\Service\RankingService;
use Hr\Controller\BaseController;

class RankingController extends BaseController
{
    public function __construct(private RankingService $service, private AddRankingForm $form)
    {
        $this->addBreadcrumbsPart('Zlecenia');
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $id = $this->params('id');
            $userId = $this->params()->fromPost('user');

            if (!empty($userId)) {
                $this->service->add($id, $userId);
                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany');
            }

            return $this->redirect()->toRoute('assembly-orders/rankings', ['id' => $id]);
        }

        return [
            'ranking' => $this->service->getRankingForOrder($this->params('id')),
            'form' => $this->form,
        ];
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->service->delete($this->params('id'));
        }

        return $this->getResponse()->setContent('ok');
    }

    public function moveAction()
    {
        $id = $this->params('id');
        $to = $this->params()->fromQuery('to');
        $ranking = $this->service->move($id, $to);

        $this->flashMessenger()->addSuccessMessage('Pozycja została zapisana');

        return $this->redirect()->toRoute('assembly-orders/rankings', ['id' => $ranking->getOrder()->getId()]);
    }
}