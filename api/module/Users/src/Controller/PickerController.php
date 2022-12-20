<?php

namespace Users\Controller;

use Doctrine\Persistence\ObjectManager;
use Hr\Entity\RecordPickerFilter;
use Hr\RecordPicker\RecordPickerService;
use Hr\Table\TableService;
use Users\Entity\User;
use Users\Form\UserSearchForm;
use Users\Table\UserTable;
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
     * @var UserTable
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
     * @var UserSearchForm
     */
    private $searchForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager       $objectManager
     * @param UserTable           $tableService
     * @param PhpRenderer         $phpRenderer
     * @param RecordPickerService $pickerService
     * @param UserSearchForm      $searchForm
     */
    public function __construct(
        ObjectManager       $objectManager,
        UserTable           $tableService,
        PhpRenderer         $phpRenderer,
        RecordPickerService $pickerService,
        UserSearchForm      $searchForm
    )
    {
        $this->objectManager = $objectManager;
        $this->tableService = $tableService;
        $this->phpRenderer = $phpRenderer;
        $this->pickerService = $pickerService;
        $this->searchForm = $searchForm;
    }

    public function indexAction()
    {
        $params = $this->params()->fromQuery();

        $this->tableService->init();
        $this->tableService->setOption(TableService::OPT_SHOW_EDIT, false);
        $records = $this->objectManager->getRepository(RecordPickerFilter::class)->findRecordsArray(
            $this->identity()['id'], 'userPickerSearch'
        );
        $this->tableService->setRowAttribute('class', function (User $row) use ($records) {
            return isset($records[$row->getId()]) ? 'info' : '';
        });
        $this->tableService->setRepository(
            $this->objectManager->getRepository(User::class),
            $params
        );
        $this->searchForm->setData($params);

        $vm = new ViewModel(['table' => $this->tableService, 'search' => $this->searchForm]);
        $vm->setTemplate('users/picker/index');
        $vm->setTerminal(true);

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
            if (strcmp($action, 'addAll') == 0 || strcmp($action, 'clearAll') == 0 || strcmp($action, 'addSelected') == 0) {
                $data = $this->pickerService->saveAddOrClear(
                    'userPickerSearch',
                    $action,
                    $this->objectManager->getRepository(User::class), $this->params()->fromPost()
                );
                $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $this->getResponse()->setContent(json_encode($data));
            } else {
                if (!empty($id)) {
                    $this->pickerService->save('userPickerSearch', $action, $id);
                    $this->getResponse()->setContent('ok');
                }
            }
        }

        return $this->getResponse();
    }
}