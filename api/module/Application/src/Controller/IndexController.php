<?php

namespace Application\Controller;

use Application\Form\LoginForm;
use AssemblyOrders\Job\AssemblyOrderAccepted;
use Doctrine\Persistence\ObjectManager;
use Hr\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Users\Entity\User;
use Users\Service\UserService;

class IndexController extends AbstractActionController
{
    /**
     * IndexController constructor.
     *
     * @param AuthenticationService $authenticationService
     * @param ObjectManager         $objectManager
     * @param UserService           $userService
     */
    public function __construct(
        private AuthenticationService $authenticationService,
        private ObjectManager         $objectManager,
        private UserService           $userService,
    ) {
    }

    public function indexAction()
    {
        $data = [
            'content' => serialize(['a' => 1, 'b' => 2]),
            'metadata' => [],
        ];

        //        echo json_encode($data);
        //        die();

        // $this->queue('assembly-order-accepted')
        //     ->push(AssemblyOrderAccepted::class, ['id' => 1, 'user_id' => 2, 'date' => time()]);
    }

    public function loginAction()
    {
        $path = $this->getRequest()->getUri()->getPath();

        $this->layout('layout/login');

        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $result = $this->authenticationService->authenticateWithPassword($data['login'], $data['password']);

            if ($result) {
                $this->userService->updateLastLoginDate($this->identity()['id']);
                $returnUri = $this->params()->fromQuery('return');

                if (!empty($returnUri)) {
                    $this->redirect()->toUrl($returnUri);
                } else {
                    $this->redirect()->toRoute('home');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Wprowadzono niepoprawny login lub hasło');
                $this->redirect()->toUrl($path);
            }
        }

        $vm = new ViewModel(['form' => $form]);

        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function logoutAction()
    {
        $user = $this->objectManager->find(User::class, $this->identity()['id']);
        $this->authenticationService->clearIdentity();

        return $this->redirect()->toUrl('/');
    }
}
