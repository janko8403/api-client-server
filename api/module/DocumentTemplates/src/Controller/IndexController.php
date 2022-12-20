<?php

namespace DocumentTemplates\Controller;

use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Form\DocumentTemplateSearchForm;
use DocumentTemplates\Table\DocumentTemplateTable;
use Hr\Controller\BaseController;
use Laminas\View\Model\ViewModel;

/**
 * Controller responsible for displaying a list of document templates.
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - Hr\Table\TableService
 * - DocumentTemplates\Form\DocumentTemplateSearchForm
 *
 * @package DocumentTemplates\Controller
 */
class IndexController extends BaseController
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var DocumentTemplateTable
     */
    private $tableService;

    /**
     * @var DocumentTemplateSearchForm
     */
    private $documentTemplateSearchForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager              $objectManager
     * @param DocumentTemplateSearchForm $documentTemplateSearchForm
     * @param DocumentTemplateTable      $tableService
     */
    public function __construct(
        ObjectManager              $objectManager,
        DocumentTemplateSearchForm $documentTemplateSearchForm,
        DocumentTemplateTable      $tableService
    )
    {
        $this->objectManager = $objectManager;
        $this->tableService = $tableService;
        $this->documentTemplateSearchForm = $documentTemplateSearchForm;
        $this->addBreadcrumbsPart('Dokumenty')->addBreadcrumbsPart('Szablony dokumentów');
    }

    public function indexAction()
    {
        $params = $this->params()->fromQuery();

        $this->tableService->init();
        $this->tableService->setRepository($this->objectManager->getRepository(DocumentTemplate::class), $params);

        $this->documentTemplateSearchForm->setData($params);

        $vm = new ViewModel(['table' => $this->tableService, 'search' => $this->documentTemplateSearchForm]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function activateAction()
    {
        return $this->activation(true, DocumentTemplate::class);
    }

    public function deactivateAction()
    {
        return $this->activation(false, DocumentTemplate::class);
    }
}