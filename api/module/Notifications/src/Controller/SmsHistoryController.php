<?php


namespace Notifications\Controller;


use Doctrine\Persistence\ObjectManager;
use Notifications\Entity\SmsHistory;
use Notifications\Form\SmsHistorySearchForm;
use Notifications\Table\SmsHistoryTable;
use Hr\Controller\BaseController;
use Hr\Table\TableService;

class SmsHistoryController extends BaseController
{
    /**
     * @var SmsHistoryTable
     */
    private $table;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var SmsHistorySearchForm
     */
    private $searchForm;

    /**
     * SmsHistoryController constructor.
     *
     * @param ObjectManager        $objectManager
     * @param SmsHistoryTable      $table
     * @param SmsHistorySearchForm $searchForm
     * @throws \Exception
     */
    public function __construct(ObjectManager $objectManager, SmsHistoryTable $table, SmsHistorySearchForm $searchForm)
    {
        $this->table = $table;
        $table->setOption(TableService::OPT_SHOW_EDIT, false);

        $this->objectManager = $objectManager;
        $this->searchForm = $searchForm;
    }

    public function indexAction()
    {
        $params = $this->params()->fromQuery();

        $this->table->init();
        $this->table->setRepository($this->objectManager->getRepository(SmsHistory::class), $params);
        $this->searchForm->setData($params);

        return ['table' => $this->table, 'search' => $this->searchForm];
    }
}