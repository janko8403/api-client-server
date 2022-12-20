<?php

namespace Settings\Controller;

use Doctrine\Persistence\ObjectManager;
use Settings\Form\DictionaryDetailForm;
use Settings\Service\DictionaryService;
use Hr\Controller\BaseController;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Hr\Entity\DictionaryDetailsDescription;
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
class DictionariesOldController extends BaseController
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
        private ObjectManager        $objectManager,
        private DictionaryDetailForm $detailForm,
        private PhpRenderer          $renderer,
        private DictionaryService    $dictionaryService
    )
    {
        $this->addBreadcrumbsPart('Ustawienia')->addBreadcrumbsPart('Słowniki S2');
    }

    public function indexAction(): array
    {
        $dictionaries = $this->objectManager->getRepository(Dictionary::class)->findBy([], ['name' => 'asc']);
        return ['dictionaries' => $dictionaries];
    }

    public function detailsAction(): ViewModel
    {
        $id = $this->params('id');
        $dictionary = $this->objectManager->find(Dictionary::class, $id);
        $this->addBreadcrumbsPart($dictionary->getName());

        $details = $this->objectManager->getRepository(DictionaryDetails::class)->findBy(['dictionary' => $id]);

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

        $detail = new DictionaryDetails();
        $detail->setDictionary($dictionary);
        if ($parent = $this->params('parent')) {
            $parent = $this->objectManager->find(DictionaryDetails::class, $parent);
            $detail->setParent($parent);
        }

        $this->detailForm->setHydrationEntity('Hr\Entity\DictionaryDetails');
        $this->detailForm->bind($detail);

        return $this->saveAndReturn($detail);
    }

    public function editAction(): JsonModel
    {
        $id = $this->params('id');

        $detail = $this->objectManager->find(DictionaryDetails::class, $id);
        $this->detailForm->setHydrationEntity('Hr\Entity\DictionaryDetails');
        $this->detailForm->bind($detail);

        return $this->saveAndReturn($detail);
    }

    public function connectedAction(): ViewModel
    {
        $id = $this->params('id');
        $detail = $this->objectManager->find(DictionaryDetails::class, $id);
        $dictionary = $this->objectManager->getRepository(Dictionary::class)->findOneBy(['parent' => $detail->getDictionary()]);
        $details = $this->objectManager->getRepository(DictionaryDetails::class)->findBy(['parent' => $detail]);

        $this->addBreadcrumbsPart($detail->getDictionary()->getName())
            ->addBreadcrumbsPart($detail->getName())
            ->addBreadcrumbsPart($dictionary->getName());

        $vm = new ViewModel(['details' => $details, 'dictionary' => $dictionary]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
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
                foreach ($data['dictionaryDetail']['description'][0] as $key => $item) {
                    if (!empty($item)) {
                        $description = new DictionaryDetailsDescription();
                        $description->setKey($key);
                        $description->setName($item);
                        $description->setDictionaryDetail($detail);

                        $this->objectManager->persist($description);
                    }
                }
                $this->objectManager->flush();

                return new JsonModel(['result' => true]);
            }
        } elseif (!$detail->getId()) {
            $this->detailForm->get('dictionaryDetail')->get('isActive')->setValue(1);
        }

        $vm = new ViewModel(['form' => $this->detailForm]);
        $vm->setTerminal(true);
        $vm->setTemplate('settings/dictionaries-old/add');

        return new JsonModel([
            'html' => $this->renderer->render($vm),
            'result' => false,
        ]);
    }
}