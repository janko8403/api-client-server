<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.10.17
 * Time: 11:58
 */

namespace Users\Controller;

use Configuration\Entity\Position;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Hr\Entity\DictionaryDetails;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\View\Model\ViewModel;
use Users\Entity\User;
use Users\Form\UserForm;
use Users\Service\UserService;

class SaveController extends BaseController
{
    protected ObjectManager $objectManager;

    private UserForm $userForm;

    private UserService $userService;

    public function __construct(
        ObjectManager $objectManager,
        UserForm      $userForm,
        UserService   $userService
    )
    {
        $this->objectManager = $objectManager;
        $this->userForm = $userForm;
        $this->userService = $userService;
        $this->addBreadcrumbsPart('Użytkownicy');
    }

    public function addAction()
    {
        $this->addBreadcrumbsPart('Dodaj');
        $user = new User();
        $vm = new ViewModel(['form' => $this->userForm]);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->userForm->bind($user);
            $this->userForm->setData($data);
            if ($this->userForm->isValid()) {
                $password = $data['user']['password'];
                $user->setPassword((new Bcrypt())->create($password));
                $user->setCreationDate(new \DateTime());

                if (!empty($data['user']['configurationPosition'])) {
                    $position = $this->objectManager->getRepository(Position::class)->find($data['user']['configurationPosition']);
                    $user->setConfigurationPosition($position);
                }
                if (!empty($data['user']['chain'])) {
                    $chain = $this->objectManager->getRepository(DictionaryDetails::class)->find($data['user']['chain']);
                    $user->setChain($chain);
                }

                $this->objectManager->persist($user);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('users');
            }
        }

        return $vm;
    }

    public function editAction()
    {
        $this->addBreadcrumbsPart('Edytuj');

        $vm = new ViewModel(['form' => $this->userForm]);
        $vm->setTemplate('users/save/add');
        $user = $this->objectManager->getRepository(User::class)->find($this->params('id'));
        $this->userForm->bind($user);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->userForm->setData($data);

            if (empty($data['user']['password'])) {
                $this->userForm->getBaseFieldset()->remove('password2')->remove('password');
                $inputFilter = $this->userForm->getBaseFieldset()->getInputFilterSpecification();
                unset($inputFilter['password']);
                $this->userForm->getBaseFieldset()->setInputFilterSpecification($inputFilter);
            }
            
            if ($this->userForm->isValid()) {
                $this->userService->save($user, $data);

                $this->flashMessenger()->addSuccessMessage('Rekord został zaktualizowany.');
                $this->redirect()->toRoute('users');
            } else {
                dd($this->userForm->getMessages());
            }
        }

        return $vm;
    }
}