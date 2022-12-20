<?php

namespace AssemblyOrders\Controller;

use AssemblyOrders\Entity\AssemblyOrder;
use AssemblyOrders\Form\AssemblyOrderForm;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Laminas\View\Model\ViewModel;

class SaveController extends BaseController
{

    public function __construct(private ObjectManager $objectManager, private AssemblyOrderForm $form)
    {
    }

    public function addAction()
    {
        $assemblyOrder = new AssemblyOrder();
        $vm = new ViewModel(['form' => $this->form]);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->form->bind($assemblyOrder);
            $this->form->setData($data);

            if ($this->form->isValid()) {
                $this->objectManager->persist($assemblyOrder);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany');
                $this->redirect()->toRoute('assembly-orders');

            }
        }

        return $vm;
    }

    public function editAction()
    {
        $assemblyOrder = $this->objectManager->find(AssemblyOrder::class, $this->params('id'));
        $vm = new ViewModel(['form' => $this->form]);
        $vm->setTemplate('assembly-orders/save/add');
        $this->form->bind($assemblyOrder);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->form->setData($data);

            if ($this->form->isValid()) {
                $this->objectManager->persist($assemblyOrder);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany');
                $this->redirect()->toRoute('assembly-orders');

            }
        }

        return $vm;
    }
}