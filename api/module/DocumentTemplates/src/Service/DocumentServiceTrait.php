<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 29.05.2017
 * Time: 15:43
 */

namespace DocumentTemplates\Service;


trait DocumentServiceTrait
{
    /**
     * @var DocumentService
     */
    protected $documentService;

    /**
     * @param DocumentService $documentService
     */
    public function setDocumentService(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }
}