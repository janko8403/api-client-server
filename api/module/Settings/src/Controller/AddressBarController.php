<?php

namespace Settings\Controller;

use Settings\Entity\AddressBar;
use Settings\Form\AddressBarForm;
use Settings\Service\AddressBarService;
use Hr\Controller\BaseController;

/**
 * Controller responsible for managing address bar
 *
 * Dependencies:
 * - Settings\Form\AddressBarForm
 * - Settings\Service\AddressBarService
 *
 * @package Settings\Controller
 */
class AddressBarController extends BaseController
{
    private AddressBarService $addressBarService;

    private AddressBarForm $addressBarForm;

    /**
     * IndexController constructor.
     *
     * @param AddressBarService $addressBarService
     * @param AddressBarForm    $addressBarForm
     */
    public function __construct(
        AddressBarService $addressBarService,
        AddressBarForm    $addressBarForm
    )
    {
        $this->addressBarService = $addressBarService;
        $this->addressBarForm = $addressBarForm;
        $this->addBreadcrumbsPart('Ustawienia')->addBreadcrumbsPart('Belka adresowa');
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->addressBarService->save($data);
            $this->flashMessenger()->addSuccessMessage('Ustawienia zostały zapisane');
            $this->redirect()->toRoute('settings/address-bar');
        } else {
            $data = $this->addressBarService->getAll();
            $this->addressBarForm->populateValues($data);
        }

        return ['form' => $this->addressBarForm, 'tags' => AddressBar::getTags()];
    }
}