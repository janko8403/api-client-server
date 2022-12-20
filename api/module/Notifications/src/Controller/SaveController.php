<?php

namespace Notifications\Controller;

use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\DocumentTemplate;
use Laminas\View\Model\ViewModel;
use Notifications\Entity\Notification;
use Notifications\Form\NotificationForm;
use Hr\Controller\BaseController;

class SaveController extends BaseController
{
    private ObjectManager $objectManager;

    private NotificationForm $form;

    /**
     * SaveController constructor.
     *
     * @param ObjectManager    $objectManager
     * @param NotificationForm $form
     */
    public function __construct(
        ObjectManager    $objectManager,
        NotificationForm $form
    )
    {
        $this->objectManager = $objectManager;
        $this->form = $form;
        $this->form->setColumnLayout();
        $this->addBreadcrumbsPart('Wiadomości')->addBreadcrumbsPart('Szablony');
    }

    public function addAction()
    {
        $this->addBreadcrumbsPart('Dodaj');

        $notification = new Notification();
        $this->form->get('notification')->get('type')
            ->setAttribute('disabled', true)
            ->setValue(Notification::TYPE_USER_DATA_VERSION_INFO);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['notification']['type'] = Notification::TYPE_USER_DATA_VERSION_INFO;
            $this->form->bind($notification);
            $this->form->setData($data);

            if ($this->form->isValid()) {
                $notification->setType(Notification::TYPE_USER_DATA_VERSION_INFO);
                $notification->setTransport(1);
                $this->objectManager->persist($notification);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('notifications');
            }
        }

        return ['form' => $this->form, 'tags' => DocumentTemplate::getTags()];
    }

    public function editAction()
    {
        $this->addBreadcrumbsPart('Edycja');

        $notification = $this->objectManager->getRepository(Notification::class)->find($this->params('id'));
        $this->form->bind($notification);
        $this->form->get('notification')->get('type')->setAttribute('disabled', true);
        $this->form->get('notification')->get('instance')->setAttribute('disabled', true);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->form->setValidationGroup(['notification' => ['subject', 'content']]);
            $this->form->setData($data);

            if ($this->form->isValid()) {
                $this->objectManager->persist($notification);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('notifications');
            }
        }

        $vm = new ViewModel(['form' => $this->form, 'tags' => DocumentTemplate::getTags()]);
        $vm->setTemplate('notifications/save/add');

        return $vm;
    }
}
