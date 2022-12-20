<?php

namespace Customers\Controller;

use Customers\Form\CustomerTemplateForm;
use Customers\Service\TemplateService;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;

class TemplateController extends BaseController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var CustomerTemplateForm
     */
    private $templateForm;

    /**
     * @var TemplateService
     */
    private $templateService;

    /**
     * TemplateController constructor.
     *
     * @param ObjectManager        $objectManager
     * @param CustomerTemplateForm $templateForm
     * @param TemplateService      $templateService
     */
    public function __construct(
        ObjectManager        $objectManager,
        CustomerTemplateForm $templateForm,
        TemplateService      $templateService
    )
    {
        $this->objectManager = $objectManager;
        $this->templateService = $templateService;
        $this->templateForm = $templateForm;
        $this->templateForm->setColumnLayout();
        $this->addBreadcrumbsPart('Klienci')->addBreadcrumbsPart('Szablon dodawania klienta');
    }

    public function indexAction()
    {
        $vm = ['form' => $this->templateForm];

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->templateService->save($data);
            $this->flashMessenger()->addSuccessMessage('Szablon został zapisany');
            $this->redirect()->toRoute('customers/template');
        } else {
            $data = $this->templateService->fetchAll();
            $this->templateForm->populateValues($data);
            $vm['autocomplete_values'] = ['city' => isset($data['customerData']['city']) ? $data['customerData']['city'] : null];
            $vm['autocomplete2'] = ['payer' => isset($data['customer']['payer']) ? $data['customer']['payer'] : null];
        }

        return $vm;
    }
}