<?php

namespace Notifications\Controller;

use Hr\Controller\BaseController;
use Laminas\View\Model\ViewModel;
use Notifications\Entity\Cycle;
use Notifications\Form\CycleForm;
use Notifications\Service\CycleService;

class CycleController extends BaseController
{
    public function __construct(private CycleService $service, private CycleForm $form)
    {
        $this->addBreadcrumbsPart('Cykle komunikacji');
    }

    public function indexAction()
    {
        return ['cycles' => $this->service->fetchAll()];
    }

    public function addAction()
    {
        $cycle = new Cycle();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->form->bind($cycle);
            $this->form->setData($data);

            if ($this->form->isValid()) {
                $this->service->save($cycle);

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('notifications/cycles');
            }
        }

        return ['form' => $this->form];
    }

    public function editAction()
    {
        $cycle = $this->service->find($this->params('id'));
        $this->form->bind($cycle);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->form->setData($data);

            if ($this->form->isValid()) {
                $this->service->save($cycle);

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('notifications/cycles');
            }
        }

        $vm = new ViewModel(['form' => $this->form]);
        $vm->setTemplate('notifications/cycle/add');

        return $vm;
    }
}