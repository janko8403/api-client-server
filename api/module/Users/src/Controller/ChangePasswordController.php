<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 03.11.17
 * Time: 09:40
 */

namespace Users\Controller;

use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Users\Entity\User;
use Users\Form\ChangePasswordForm;
use Users\Service\UserService;

class ChangePasswordController extends BaseController
{
    /**
     * ChangePasswordController constructor.
     *
     * @param ObjectManager      $objectManager
     * @param PhpRenderer        $renderer
     * @param ChangePasswordForm $changePasswordForm
     * @param UserService        $userService
     */
    public function __construct(
        private ObjectManager      $objectManager,
        private PhpRenderer        $renderer,
        private ChangePasswordForm $changePasswordForm,
        private UserService        $userService
    )
    {
        $this->addBreadcrumbsPart('Zmiana hasła');
    }

    public function userAction()
    {
        $vm = new ViewModel(['form' => $this->changePasswordForm]);
        $vm->setTemplate('users/change-password/user');
        $vm->setTerminal(true);

        $this->changePasswordForm->setAttribute('action', 'users/change-password/' . $this->params('id'));
        $this->changePasswordForm->setupSimple();

        /**
         * @var User
         */
        $user = $this->objectManager->getRepository(User::class)->find($this->params('id'));

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->changePasswordForm->bind($user);
            $this->changePasswordForm->setData($data);

            if ($this->changePasswordForm->isValid($data, $data)) {
                $this->userService->changePassword($user, $data['userPassword']['password']);

                return new JsonModel(['result' => true, 'html' => $this->renderer->render($vm)]);
            }
        }

        return new JsonModel([
            'result' => false,
            'html' => $this->renderer->render($vm),
        ]);
    }

    public function currentAction()
    {
        $vm = new ViewModel(['form' => $this->changePasswordForm]);
        $vm->setTemplate('users/change-password/user');

        /**
         * @var User
         */
        $user = $this->objectManager->getRepository(User::class)->find($this->identity()['id']);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->changePasswordForm->bind($user);
            $this->changePasswordForm->setData($data);

            if ($this->changePasswordForm->isValid()) {
                $user->setTempPassword(false);
                $this->userService->changePassword($user, $data['userPassword']['password']);

                $this->flashMessenger()->addSuccessMessage('Hasło zostało zmienione.');

                return $this->redirect()->toRoute('users/change-password/current');
            }
        }

        return $vm;
    }
}