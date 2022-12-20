<?php

namespace Settings\Controller;

use Configuration\Form\MonitoringCategoryForm;
use Doctrine\Persistence\ObjectManager;
use Monitorings\Entity\MonitoringCategory;
use Settings\Form\DictionaryDetailForm;
use Settings\Service\DictionaryService;
use Hr\Controller\BaseController;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

/**
 * Controller responsible for managing old dictionaries
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - Settings\Form\DictionaryDetailForm
 * - Laminas\View\Renderer\PhpRenderer
 * - Settings\Service\DictionaryService
 *
 * @package Settings\Controller
 */
class MonitoringCategoriesController extends BaseController
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MonitoringCategoryForm
     */
    private $categoryForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager          $objectManager
     * @param MonitoringCategoryForm $categoryForm
     */
    public function __construct(
        ObjectManager          $objectManager,
        MonitoringCategoryForm $categoryForm
    )
    {
        $this->objectManager = $objectManager;
        $this->categoryForm = $categoryForm;
        $this->addBreadcrumbsPart('Ustawienia')->addBreadcrumbsPart('Grupy monitoringów');
    }

    public function indexAction()
    {
        $categories = $this->objectManager->getRepository(MonitoringCategory::class)->findBy([], ['sequence' => 'asc']);

        return ['categories' => $categories];
    }

    public function addAction()
    {
        $this->addBreadcrumbsPart('Dodaj');
        $category = new MonitoringCategory();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->categoryForm->bind($category);
            $this->categoryForm->setData($data);

            if ($this->categoryForm->isValid()) {
                $this->objectManager->persist($category);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany');
                $this->redirect()->toRoute('settings/monitoring-categories');
            }
        }

        return ['form' => $this->categoryForm];
    }

    public function editAction()
    {
        $this->addBreadcrumbsPart('Edycja');

        $category = $this->objectManager->getRepository(MonitoringCategory::class)->find($this->params('id'));
        $this->categoryForm->bind($category);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->categoryForm->setData($data);

            if ($this->categoryForm->isValid()) {
                $this->objectManager->persist($category);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('settings/monitoring-categories');
            }
        }

        $vm = new ViewModel(['form' => $this->categoryForm]);
        $vm->setTemplate('settings/monitoring-categories/add');

        return $vm;
    }
}