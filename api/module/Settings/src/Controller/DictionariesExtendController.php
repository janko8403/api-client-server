<?php

namespace Settings\Controller;

use Doctrine\Persistence\ObjectManager;
use Settings\Service\DictionaryService;
use Hr\Controller\BaseController;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Laminas\Mvc\I18n\Translator;
use Laminas\View\Model\JsonModel;
use Laminas\View\Renderer\PhpRenderer;

/**
 * Controller responsible for extending dictionaries
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - Settings\Service\DictionaryService
 * - Laminas\Mvc\I18n\Translator
 *
 * @package Settings\Controller
 */
class DictionariesExtendController extends BaseController
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var DictionaryService
     */
    private $dictionaryService;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager     $objectManager
     * @param DictionaryService $dictionaryService
     * @param Translator        $translator
     */
    public function __construct(
        ObjectManager     $objectManager,
        PhpRenderer       $renderer,
        DictionaryService $dictionaryService,
        Translator        $translator
    )
    {
        $this->objectManager = $objectManager;
        $this->renderer = $renderer;
        $this->dictionaryService = $dictionaryService;
        $this->translator = $translator;
    }

    public function addAction()
    {
        $dictionaryId = $this->params('id');
        $dictionary = $this->objectManager->find(Dictionary::class, $dictionaryId);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            if (!empty($data['value'])) {
                $valueExists = $this->dictionaryService->checkValueExists($dictionary, $data['value']);

                if ($valueExists) {
                    return new JsonModel([
                        'result' => false,
                        'msg' => $this->translator->translate('Podana wartość już istnieje w słowniku'),
                    ]);
                } else {
                    $detail = new DictionaryDetails();
                    $detail->setDictionary($dictionary);
                    $detail->setName($data['value']);
                    $this->dictionaryService->save($detail);

                    return new JsonModel(['result' => true, 'id' => $detail->getId()]);
                }
            }
        }

        return new JsonModel(['result' => false]);
    }
}