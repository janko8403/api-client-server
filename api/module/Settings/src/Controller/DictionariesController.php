<?php

namespace Settings\Controller;

use Doctrine\Persistence\ObjectManager;
use Settings\Entity\Dictionary;
use Settings\Form\DictionaryDetailForm;
use Settings\Service\DictionaryService;
use Hr\Controller\BaseController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

/**
 * Controller responsible for managing dictionaries..
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - Settings\Form\DictionaryDetailForm
 * - Laminas\View\Renderer\PhpRenderer
 * - Settings\Service\DictionaryService
 *
 * @package Settings\Controller
 */
class DictionariesController extends BaseController
{
    /**
     * IndexController constructor.
     *
     * @param ObjectManager        $objectManager
     * @param DictionaryDetailForm $detailForm
     * @param PhpRenderer          $renderer
     * @param DictionaryService    $dictionaryService
     */
    public function __construct(
        protected ObjectManager      $objectManager,
        private DictionaryDetailForm $detailForm,
        private PhpRenderer          $renderer,
        private DictionaryService    $dictionaryService
    )
    {
        $this->addBreadcrumbsPart('Ustawienia')->addBreadcrumbsPart('Słowniki');
    }

    public function indexAction(): array
    {
        $dictionaries = $this->objectManager->getRepository(Dictionary::class)->findAll();
        return ['dictionaries' => $dictionaries];
    }

    public function detailsAction(): ViewModel
    {
        $id = $this->params('id');
        $dictionary = $this->objectManager->find(Dictionary::class, $id);
        $this->addBreadcrumbsPart($dictionary->getName());

        if (class_exists($dictionary->getEntityName())) {
            $details = $this->objectManager->getRepository($dictionary->getEntityName())->findAll();
        } else {
            $details = [];
        }

        $vm = new ViewModel(['details' => $details]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function addAction(): JsonModel
    {
        $id = $this->params('id');
        $dictionary = $this->objectManager->find(Dictionary::class, $id);
        $entityName = $dictionary->getEntityName();
        $this->detailForm->setHydrationEntity($entityName);
        $detail = new $entityName();
        $this->detailForm->bind($detail);

        return $this->saveAndReturn($detail);
    }

    public function editAction(): JsonModel
    {
        $id = $this->params('id');
        $dictionaryId = $this->params('dictionary');
        $dictionary = $this->objectManager->find(Dictionary::class, $dictionaryId);
        $entityName = $dictionary->getEntityName();
        $this->detailForm->setHydrationEntity($entityName);
        $detail = $this->objectManager->find($entityName, $id);
        $this->detailForm->bind($detail);

        return $this->saveAndReturn($detail);
    }

    /**
     * @param $detail
     * @return JsonModel
     */
    private function saveAndReturn($detail): JsonModel
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->detailForm->setData($data);

            if ($this->detailForm->isValid()) {
                $this->dictionaryService->save($detail);

                return new JsonModel(['result' => true]);
            }
        }

        $vm = new ViewModel(['form' => $this->detailForm]);
        $vm->setTerminal(true);
        $vm->setTemplate('settings/dictionaries/add');

        return new JsonModel([
            'html' => $this->renderer->render($vm),
            'result' => false,
        ]);
    }
}