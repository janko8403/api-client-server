<?php

namespace Hr\Controller;

use Customers\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Payers\Entity\Payer;
use Products\Entity\Product;
use Hr\Dictionary\DictionaryService;
use Hr\Entity\DictionaryDetails;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

/**
 * Controller responsible for handling autocomplete lists
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 *
 * @package Hr\Controller
 */
class AutocompleteController extends AbstractActionController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var DictionaryService
     */
    private $dictionaryService;

    /**
     * AutocompleteController constructor.
     *
     * @param ObjectManager     $objectManager
     * @param DictionaryService $dictionaryService
     */
    public function __construct(ObjectManager $objectManager, DictionaryService $dictionaryService)
    {
        $this->objectManager = $objectManager;
        $this->dictionaryService = $dictionaryService;
    }

    public function dictionaryAction()
    {
        $searchById = false;
        $q = $this->params()->fromQuery('q');
        $dictionaryId = $this->params('dictionary');

        if ($id = $this->params()->fromQuery('id')) {
            $q = $id;
            $searchById = true;
        }

        $data = $this->objectManager->getRepository(DictionaryDetails::class)->autocompleteSearch($dictionaryId, $q, $searchById);

        return new JsonModel($data);
    }

    public function payerAction()
    {
        $searchById = false;
        $q = $this->params()->fromQuery('q');

        if ($id = $this->params()->fromQuery('id')) {
            $q = $id;
            $searchById = true;
        }
        $data = $this->objectManager->getRepository(Payer::class)->autocompleteSearch($q, $searchById);

        return new JsonModel($data);

    }

    public function dependantValuesAction()
    {
        $dictionary = $this->params('dictionary');
        $values = $this->params()->fromQuery('values');
        $values = empty($values) ? [] : (array)$values;

        $vm = new ViewModel([
            'data' => $this->dictionaryService->getDependantValues($dictionary, $values),
        ]);
        $vm->setTerminal(true);

        return $vm;
    }

    public function productAction()
    {
        $q = $this->params()->fromQuery('q');
        $extended = (bool)$this->params()->fromQuery('extended', 0);
        $data = $this->objectManager->getRepository(Product::class)->autocompleteSearch($q, $extended);

        return new JsonModel($data);
    }

    public function customerAction()
    {
        $searchById = false;
        $q = $this->params()->fromQuery('q');

        if ($id = $this->params()->fromQuery('id')) {
            $q = $id;
            $searchById = true;
        }

        $data = $this->objectManager->getRepository(Customer::class)->searchAutocomplete($q, $searchById);

        return new JsonModel($data);
    }
}