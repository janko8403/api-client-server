<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 02:52
 */

namespace Settings\Controller;

use Doctrine\Persistence\ObjectManager;
use Settings\Form\SubchainForm;
use Settings\Form\SubchainSearchForm;
use Settings\Table\SubchainTable;
use Hr\Controller\BaseController;
use Hr\Entity\Subchain;
use Laminas\I18n\Translator\Translator;
use Laminas\View\Model\ViewModel;


class SubchainController extends BaseController
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var SubchainTable
     */
    private $subchainTable;

    /**
     * @var SubchainForm
     */
    private $subchainForm;

    /**
     * @var Translator
     */
    private $traslator;

    /**
     * @var SubchainSearchForm
     */
    private $searchForm;

    public function __construct(
        ObjectManager      $objectManager,
        SubchainTable      $subchainTable,
        SubchainForm       $subchainForm,
        Translator         $translator,
        SubchainSearchForm $subchainSearchForm
    )
    {
        $this->objectManager = $objectManager;
        $this->subchainTable = $subchainTable;
        $this->subchainForm = $subchainForm;
        $this->traslator = $translator;
        $this->searchForm = $subchainSearchForm;
        $this->addBreadcrumbsPart('Podsieci');
    }

    public function indexAction()
    {
        $params = $this->params()->fromQuery();
        $vm = new ViewModel(['table' => $this->subchainTable, 'search' => $this->searchForm]);
        $this->subchainTable->init();
        $this->subchainTable->setRepository($this->objectManager->getRepository(Subchain::class), $params);

        $this->searchForm->setData($params);

        return $vm;
    }

    public function addAction()
    {
        $this->addBreadcrumbsPart('Dodaj');
        $subchain = new Subchain();
        $this->subchainForm->getBaseFieldset()->remove('isActive');
        $vm = new ViewModel(['form' => $this->subchainForm]);
        $vm->setTemplate('settings/subchain/add');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->subchainForm->bind($subchain);
            $this->subchainForm->setData($data);
            if ($this->subchainForm->isValid()) {
                $this->objectManager->persist($subchain);

                $this->objectManager->flush();
                $this->flashMessenger()->addSuccessMessage($this->traslator->translate('Rekord został zapisany.'));
                $this->redirect()->toRoute('settings/subchains');
            }
        }


        return $vm;
    }

    public function editAction()
    {
        $this->addBreadcrumbsPart('Edytuj');

        $subchain = $this->objectManager->getRepository(Subchain::class)->find($this->params('id'));
        $this->subchainForm->getBaseFieldset()->remove('isActive');
        $vm = new ViewModel(['form' => $this->subchainForm]);
        $vm->setTemplate('settings/subchain/add');
        $this->subchainForm->bind($subchain);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->subchainForm->bind($subchain);
            $this->subchainForm->setData($data);
            if ($this->subchainForm->isValid()) {
                $this->objectManager->persist($subchain);

                $this->objectManager->flush();
                $this->flashMessenger()->addSuccessMessage($this->traslator->translate('Rekord został zapisany.'));
                $this->redirect()->toRoute('settings/subchains');
            }
        }


        return $vm;
    }
}