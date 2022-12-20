<?php

namespace DocumentTemplates\Controller;

use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Form\DocumentTemplateForm;
use Laminas\View\Model\ViewModel;
use Hr\Controller\BaseController;

/**
 * Controller responsible for adding and modifying document templates.
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - DocumentTemplates\Form\DocumentTemplateForm
 *
 * @package DocumentTemplates\Controller
 */
class SaveController extends BaseController
{
    private ObjectManager $objectManager;

    private DocumentTemplateForm $documentTemplateForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager        $objectManager
     * @param DocumentTemplateForm $DocumentTemplateForm
     */
    public function __construct(
        ObjectManager        $objectManager,
        DocumentTemplateForm $DocumentTemplateForm,
    )
    {
        $this->objectManager = $objectManager;
        $this->documentTemplateForm = $DocumentTemplateForm;
        $this->documentTemplateForm->setColumnLayout();
        $this->addBreadcrumbsPart('Dokumenty')->addBreadcrumbsPart('Szablony dokumentów');
    }

    public function addAction()
    {
        $this->addBreadcrumbsPart('Dodaj');

        $DocumentTemplate = new DocumentTemplate();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->documentTemplateForm->bind($DocumentTemplate);
            $this->documentTemplateForm->setData($data);

            if ($this->documentTemplateForm->isValid()) {
                $this->objectManager->persist($DocumentTemplate);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('document-templates');
            }
        }

        return ['form' => $this->documentTemplateForm, 'tags' => DocumentTemplate::getTags()];
    }

    public function editAction()
    {
        $this->addBreadcrumbsPart('Edycja');

        $DocumentTemplate = $this->objectManager->getRepository(DocumentTemplate::class)->find($this->params('id'));
        $this->documentTemplateForm->bind($DocumentTemplate);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->documentTemplateForm->setData($data);

            if ($this->documentTemplateForm->isValid()) {
                $this->objectManager->persist($DocumentTemplate);
                $this->objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');
                $this->redirect()->toRoute('document-templates');
            }
        }

        $vm = new ViewModel(['form' => $this->documentTemplateForm, 'tags' => DocumentTemplate::getTags()]);
        $vm->setTemplate('document-templates/save/add');

        return $vm;
    }
}
