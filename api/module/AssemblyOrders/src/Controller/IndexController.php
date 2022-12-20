<?php

namespace AssemblyOrders\Controller;

use AssemblyOrders\Entity\AssemblyOrder;
use AssemblyOrders\Form\AssembyOrderSearchForm;
use AssemblyOrders\Table\AssemblyOrderTable;
use Customers\Entity\UserRelationJoint;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Laminas\View\Model\ViewModel;

class IndexController extends BaseController
{

    public function __construct(
        private ObjectManager          $objectManager,
        private AssemblyOrderTable     $table,
        private AssembyOrderSearchForm $searchForm
    )
    {
        $this->addBreadcrumbsPart('Zlecenia');
    }

    public function indexAction()
    {
        $vm = new ViewModel(['table' => $this->table, 'search' => $this->searchForm]);
        $this->table->init();
        $params = $this->params()->fromQuery();
        $params['accepted'] ??= '2';
        $params['perPage'] ??= 10;

        $customers = $this->objectManager->getRepository(UserRelationJoint::class)->fetchCustomersForUser($this->identity()['id']);
        $params['customers'] = array_map(fn($j) => $j->getCustomer()->getId(), $customers);

        $this->searchForm->setData($params);
        $this->table->setRepository($this->objectManager->getRepository(AssemblyOrder::class), $params);

        return $vm;
    }
}