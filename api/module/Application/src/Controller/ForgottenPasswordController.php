<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 16.04.18
 * Time: 12:05
 */

namespace Application\Controller;

use Doctrine\Persistence\ObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer as Renderer;
use Users\Form\ForgottenPasswordForm;
use Users\Form\ResetPasswordForm;
use Users\Service\RegistrationService;

class ForgottenPasswordController extends AbstractActionController
{
    public function __construct(
        private ObjectManager       $objectManager,
        private Renderer            $renderer,
        private RegistrationService $registrationService
    )
    {
    }

    public function indexAction()
    {
        $result = false;
        $message = false;
        $form = new ForgottenPasswordForm($this->objectManager);
        $vm = new ViewModel(['form' => $form]);
        $vm->setTerminal(true);
        $vm->setTemplate('application/forgotten-password/index');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $data['login'] = ['login' => $data['login'], 'isactive' => true];

            $form->setData($data);
            if ($form->isValid($data, $data)) {
                [$result, $message] = $this->registrationService->sendResetPasswordCode($form->getData());
                if ($message) {
                    $this->flashMessenger()->addSuccessMessage($message);
                    $form->get('login')->setValue($data['login']['login'] ?? '');
                }
            } else {
                $form->get('login')->setValue($data['login']['login'] ?? '');
            }
        }

        return new JsonModel(['result' => $result, 'message' => $message, 'html' => $this->renderer->render($vm)]);
    }

    public function changeAction()
    {
        $this->layout('layout/login');
        $form = new ResetPasswordForm($this->objectManager);
        $vm = new ViewModel(['form' => $form]);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->registrationService->saveNewPassword($form->getData());
                $this->flashMessenger()->addSuccessMessage('Hasło zostało zmienione');
                $this->redirect()->toRoute('home');
            }
        }

        return $vm;
    }
}