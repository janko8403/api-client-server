<?php

namespace Users\Controller;

use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Laminas\View\Model\ViewModel;
use Users\Entity\User;
use Users\Form\UserSearchForm;
use Users\Table\UserTable;

class IndexController extends BaseController
{
    protected ObjectManager $objectManager;

    private UserTable $userTable;

    private UserSearchForm $userSearchForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager  $objectManager
     * @param UserTable      $userTable
     * @param UserSearchForm $userSearchForm
     */
    public function __construct(
        ObjectManager  $objectManager,
        UserTable      $userTable,
        UserSearchForm $userSearchForm
    )
    {
        $this->objectManager = $objectManager;
        $this->userTable = $userTable;
        $this->userSearchForm = $userSearchForm;
        $this->addBreadcrumbsPart('Użytkownicy')->addBreadcrumbsPart('Lista');
    }

    public function indexAction()
    {
        $this->userTable->init();
        $params = $this->params()->fromQuery();

        if (empty($params['perPage'])) {
            $params['perPage'] = 10;
        }

        $this->userTable->setRepository($this->objectManager->getRepository(User::class), $params);
        $this->userSearchForm->setData($params);
        $vm = new ViewModel(['table' => $this->userTable, 'search' => $this->userSearchForm]);
        $vm->setVariable('autocomplete_values', [
            'customer' => $params['customer'] ?? null,
        ]);

        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function activateAction()
    {
        return $this->activation(true, User::class);
    }

    public function deactivateAction()
    {
        $id = $this->params('id');
        $user = $this->objectManager->find(User::class, $id);
        $this->objectManager->getRepository(User::class)->clearUserSessions($user);

        return $this->activation(false, User::class);
    }
}
