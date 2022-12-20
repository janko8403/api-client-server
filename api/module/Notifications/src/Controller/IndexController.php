<?php

namespace Notifications\Controller;

use Doctrine\Persistence\ObjectManager;
use Laminas\View\Model\ViewModel;
use Notifications\Entity\Notification;
use Notifications\Form\NotificationSearchForm;
use Notifications\Table\NotificationTable;
use Hr\Controller\BaseController;

class IndexController extends BaseController
{
    private ObjectManager $objectManager;

    private NotificationSearchForm $searchForm;

    private NotificationTable $table;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager          $objectManager
     * @param NotificationSearchForm $searchForm
     * @param NotificationTable      $table
     */
    public function __construct(
        ObjectManager          $objectManager,
        NotificationSearchForm $searchForm,
        NotificationTable      $table
    )
    {
        $this->addBreadcrumbsPart('Wiadomości')->addBreadcrumbsPart('Szablony');
        $this->objectManager = $objectManager;
        $this->searchForm = $searchForm;
        $this->table = $table;
    }

    public function indexAction()
    {
        $params = $this->params()->fromQuery();

        $this->table->init();
        $this->table->setRepository($this->objectManager->getRepository(Notification::class), $params);

        $this->searchForm->setData($params);

        $vm = new ViewModel(['table' => $this->table, 'search' => $this->searchForm]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }
}