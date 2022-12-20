<?php

namespace Customers\Controller;

use Customers\Entity\Customer;
use Customers\Form\CustomerPickerSearchForm;
use Customers\Table\CustomerTable;
use Doctrine\Persistence\ObjectManager;
use Settings\Entity\PositionVisibility;
use Settings\Service\PositionVisibilityService;
use Hr\Entity\RecordPickerFilter;
use Hr\RecordPicker\RecordPickerService;
use Hr\Table\TableService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

class PickerController extends AbstractActionController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var CustomerTable
     */
    private $tableService;

    /**
     * @var PhpRenderer
     */
    private $phpRenderer;

    /**
     * @var RecordPickerService
     */
    private $pickerService;

    /**
     * @var CustomerPickerSearchForm
     */
    private $customerPickerSearchForm;

    /**
     * @var PositionVisibilityService
     */
    private $positionVisibilityService;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager             $objectManager
     * @param CustomerPickerSearchForm  $customerPickerSearchForm
     * @param CustomerTable             $tableService
     * @param PhpRenderer               $phpRenderer
     * @param RecordPickerService       $pickerService
     * @param PositionVisibilityService $positionVisibilityService
     */
    public function __construct(
        ObjectManager             $objectManager,
        CustomerPickerSearchForm  $customerPickerSearchForm,
        CustomerTable             $tableService,
        PhpRenderer               $phpRenderer,
        RecordPickerService       $pickerService,
        PositionVisibilityService $positionVisibilityService
    )
    {
        $this->objectManager = $objectManager;
        $this->tableService = $tableService;
        $this->phpRenderer = $phpRenderer;
        $this->pickerService = $pickerService;
        $this->customerPickerSearchForm = $customerPickerSearchForm;
        $this->positionVisibilityService = $positionVisibilityService;
    }

    public function indexAction()
    {
        $params = $this->params()->fromQuery();

        $identity = $this->identity();
        $params['only_inside_region'] = true;
        $params['regionsVisibility'] = $this->positionVisibilityService->getListOfRegions(
            PositionVisibility::FIELD_CUSTOMERS_PICKER,
            $identity['configurationPositionId'],
            $identity['regionDicId'],
            $identity['supervisorId']
        );

        // save picker state for possible rollback
        if (isset($params['save-state'])) {
            $this->pickerService->saveState('customerPickerSearch');
        }

        $this->tableService->init();
        $this->tableService->setOption(TableService::OPT_SHOW_EDIT, false);
        $records = $this->objectManager->getRepository(RecordPickerFilter::class)->findRecordsArray(
            $this->identity()['id'], 'customerPickerSearch'
        );
        $this->tableService->setRowAttribute('class', function (Customer $row) use ($records) {
            return isset($records[$row->getId()]) ? 'info' : '';
        });
        $this->tableService->setRepository(
            $this->objectManager->getRepository(Customer::class),
            $params
        );
        $this->customerPickerSearchForm->setData($params);

        $vm = new ViewModel(['table' => $this->tableService, 'search' => $this->customerPickerSearchForm]);
        $vm->setTemplate('customers/picker/index');
        $vm->setTerminal(true);
        $vm->setVariable('autocomplete_values', [
            'city' => $params['city'] ?? null,
        ]);

        return new JsonModel([
            'html' => $this->phpRenderer->render($vm),
            'count' => count($records),
        ]);
    }

    public function selectAction()
    {
        if ($this->getRequest()->isPost()) {
            $action = $this->params()->fromPost('action');
            $id = $this->params()->fromPost('id');

            $searchParams = $this->params()->fromPost();
            $identity = $this->identity();
            $params['only_inside_region'] = true;
            $params['regionsVisibility'] = $this->positionVisibilityService->getListOfRegions(
                PositionVisibility::FIELD_CUSTOMERS_PICKER,
                $identity['configurationPositionId'],
                $identity['regionDicId'],
                $identity['supervisorId']
            );


            if (strcmp($action, 'addAll') == 0 || strcmp($action, 'clearAll') == 0 || strcmp($action, 'addSelected') == 0) {
                $data = $this->pickerService->saveAddOrClear(
                    'customerPickerSearch',
                    $action,
                    $this->objectManager->getRepository(Customer::class),
                    array_merge($searchParams, $params),
                    $params
                );
                $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $this->getResponse()->setContent(json_encode($data));
            } elseif ($action == 'cancelChanges') {
                $this->pickerService->cancelChanges('customerPickerSearch');
            } else {
                if (!empty($id)) {
                    $this->pickerService->save('customerPickerSearch', $action, $id);
                    $this->getResponse()->setContent('ok');
                }
            }
        }

        return $this->getResponse();
    }
}