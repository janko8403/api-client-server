<?php

namespace Customers\Controller;

use Customers\Entity\Customer;
use Customers\Form\CustomerPickerSearchForm;
use Customers\Service\ExportService;
use Customers\Table\CustomerTable;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;
use Settings\Entity\PositionVisibility;
use Settings\Service\PositionVisibilityService;

class IndexController extends BaseController
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var CustomerTable
     */
    private $customerTable;

    /**
     * @var CustomerPickerSearchForm
     */
    private $customerSearchForm;

    /**
     * @var PositionVisibilityService
     */
    private $positionVisibilityService;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager             $objectManager
     * @param CustomerTable             $customerTable
     * @param CustomerPickerSearchForm  $searchForm
     * @param PositionVisibilityService $positionVisibilityService
     */
    public function __construct(
        ObjectManager             $objectManager,
        CustomerTable             $customerTable,
        CustomerPickerSearchForm  $searchForm,
        PositionVisibilityService $positionVisibilityService,
    )
    {
        $this->objectManager = $objectManager;
        $this->customerTable = $customerTable;
        $this->customerSearchForm = $searchForm;
        $this->positionVisibilityService = $positionVisibilityService;
        $this->addBreadcrumbsPart('Klienci')->addBreadcrumbsPart('Lista');
    }

    public function indexAction()
    {
        $container = new Container('customers');
        $container->return_url = $this->getRequest()->getRequestUri();

        $vm = new ViewModel(['table' => $this->customerTable, 'search' => $this->customerSearchForm]);
        $this->customerTable->init();
        $params = $this->params()->fromQuery();
        if (empty($params['sort'])) {
            $params['sort']['cd.name'] = 'asc';
        }
        if (empty($params['perPage'])) {
            $params['perPage'] = 10;
        }

        $identity = $this->identity();
        $params['restrict_search_region'] = true;
        $params['regionsVisibility'] = $this->positionVisibilityService->getListOfRegions(
            PositionVisibility::FIELD_CUSTOMERS,
            $identity['configurationPositionId'],
            $identity['regionDicId'],
            $identity['supervisorId'],
            $identity['id']
        );
        $this->customerTable->setRepository($this->objectManager->getRepository(Customer::class), $params);
        $this->customerSearchForm->setData($params);
        $vm->setVariable('autocomplete_values', [
            'city' => $params['city'] ?? null,
        ]);

        return $vm;
    }

    public function activateAction()
    {
        return $this->activation(true, Customer::class);
    }

    public function deactivateAction()
    {
        return $this->activation(false, Customer::class);
    }
}