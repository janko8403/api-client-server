<?php

namespace Customers\Controller;

use Customers\Entity\Customer;
use Customers\Entity\UserRelationJoint;
use Customers\Form\AddUserForm;
use Customers\Form\UserForm;
use Customers\Service\UserRelationService;
use Doctrine\Persistence\ObjectManager;
use Laminas\View\Model\ViewModel;
use Hr\Controller\BaseController;
use Users\Entity\User;

class UsersController extends BaseController
{
    /**
     * UsersController constructor.
     *
     * @param ObjectManager       $objectManager
     * @param UserForm            $form
     * @param UserRelationService $service
     * @param AddUserForm         $addUserForm
     */
    public function __construct(
        private ObjectManager       $objectManager,
        private UserForm            $form,
        private UserRelationService $service,
        private AddUserForm         $addUserForm
    )
    {
    }

    public function indexAction(): ViewModel
    {
        $id = $this->params('id');
        $this->addBreadcrumb();
        $users = $this->objectManager->getRepository(UserRelationJoint::class)->findBy(['customer' => $id]);

        $vm = new ViewModel([
            'users' => $users,
        ]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function addAction(): array
    {
        $this->addBreadcrumb();
        $id = $this->params('id');
        $relation = new UserRelationJoint();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->form->bind($relation);
            $this->form->setData($data);
            if ($this->form->isValid()) {
                $relation->setCustomer($this->objectManager->find(Customer::class, $id));
                $this->objectManager->persist($relation);
                $this->objectManager->flush();
                $this->flashMessenger()->addSuccessMessage('Dane zostały zapisane.');
                $this->redirect()->toRoute('customers/users', ['id' => $id]);
            }
        }

        return ['form' => $this->form];
    }

    public function editAction(): ViewModel
    {
        $this->addBreadcrumb();
        $this->addBreadcrumbsPart('Edycja');
        $id = $this->params('id');
        $relation = $this->objectManager->find(UserRelationJoint::class, $id);
        $this->form->bind($relation);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->form->setData($data);
            if ($this->form->isValid()) {
                $this->objectManager->persist($relation);
                $this->objectManager->flush();
                $this->flashMessenger()->addSuccessMessage('Dane zostały zapisane.');
                $this->redirect()->toRoute('customers/users', ['id' => $relation->getCustomer()->getId()]);
            }
        }

        $vm = new ViewModel(['form' => $this->form]);
        $vm->setTemplate('customers/users/add');

        return $vm;
    }

    public function deleteAction(): mixed
    {
        $id = $this->params('id');

        if ($this->getRequest()->isPost()) {
            $relation = $this->objectManager->find(UserRelationJoint::class, $id);
            $this->objectManager->remove($relation);
            $this->objectManager->flush();
            $this->getResponse()->setContent('ok');
        }

        return $this->getResponse();
    }

    /**
     * @throws \Exception
     */
    public function addUserAction(): array
    {
        $id = $this->params('id');
        $customer = $this->objectManager->find(Customer::class, $id);
        $this->addBreadcrumb();

        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();
            $this->addUserForm->setData($post);

            if ($this->addUserForm->isValid()) {
                $this->service->addUserWithRelation($this->addUserForm->getData(), $customer);
                $this->flashMessenger()->addSuccessMessage('Dane zostały zapisane.');
                $this->redirect()->toRoute('customers/users', ['id' => $id]);
            }
        } else {
            $this->addUserForm->setData($this->service->getDefaultAddUserData());
        }

        return ['form' => $this->addUserForm];
    }

    public function emailAction(): mixed
    {
        if ($this->getRequest()->isPost()) {
            $user = $this->objectManager->find(User::class, $this->params('id'));
            $user->setEmail($this->params()->fromPost('value'));
            $this->objectManager->persist($user);
            $this->objectManager->flush();
            $this->getResponse()->setContent('ok');
        }

        return $this->getResponse();
    }

    public function phoneAction(): mixed
    {
        if ($this->getRequest()->isPost()) {
            $user = $this->objectManager->find(User::class, $this->params('id'));
            $user->setPhonenumber($this->params()->fromPost('value'));
            $this->objectManager->persist($user);
            $this->objectManager->flush();
            $this->getResponse()->setContent('ok');
        }

        return $this->getResponse();
    }

    private function addBreadcrumb()
    {
        $id = $this->params('id');
        $customer = $this->objectManager->find(Customer::class, $id);
        $this->addBreadcrumbsPart($customer->getActiveData()->getName());
    }
}