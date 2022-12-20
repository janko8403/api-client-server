<?php

namespace Settings\Controller;

use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Settings\Entity\NotificationHour;

class NotificationHoursController extends BaseController
{
    public function __construct(private ObjectManager $objectManager)
    {
        $this->addBreadcrumbsPart('Godziny powiadomień');
    }

    public function indexAction()
    {
        return ['hours' => $this->objectManager->getRepository(NotificationHour::class)->findAll()];
    }

    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();

            foreach ($post['from'] as $id => $time) {
                $from = new \DateTime($time);
                $to = new \DateTime($post['to'][$id]);

                $notification = $this->objectManager->find(NotificationHour::class, $id);
                $notification->setFrom($from);
                $notification->setTo($to);
                $this->objectManager->persist($notification);
            }

            $this->objectManager->flush();
            $this->flashMessenger()->addSuccessMessage('Dane zostały zapisane');
        }

        $this->redirect()->toRoute('settings/notification-hours');
    }
}