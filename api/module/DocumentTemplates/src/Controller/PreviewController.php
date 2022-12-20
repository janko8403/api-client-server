<?php

namespace DocumentTemplates\Controller;

use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\Document;
use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Service\DocumentService;
use Hr\Content\PdfService;
use Hr\Controller\BaseController;

/**
 * Controller responsible for displaying a preview of a document template.
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - Hr\Content\PdfService
 *
 * @package DocumentTemplates\Controller
 */
class PreviewController extends BaseController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var PdfService
     */
    private $pdfService;

    /**
     * @var DocumentService
     */
    private $documentService;

    /**
     * PreviewController constructor.
     *
     * @param ObjectManager   $objectManager
     * @param PdfService      $pdfService
     * @param DocumentService $documentService
     */
    public function __construct(ObjectManager $objectManager, PdfService $pdfService, DocumentService $documentService)
    {
        $this->objectManager = $objectManager;
        $this->pdfService = $pdfService;
        $this->documentService = $documentService;
    }

    public function indexAction()
    {
        $template = $this->objectManager->find(DocumentTemplate::class, $this->params('id'));

        if ($template) {
            $this->pdfService->generate(
                $template->getContentBody(),
                $template->getName() . '.pdf',
                'D',
                $template->getContentHeader(),
                $template->getContentFooter()
            );
        }

        return $this->getResponse();
    }

    public function downloadAction()
    {
        $fulfilmentId = $this->params('id');
        $document = $this->objectManager->getRepository(Document::class)->findOneBy(['fulfilment' => $fulfilmentId]);

        return $this->documentService->download($document);
    }
}