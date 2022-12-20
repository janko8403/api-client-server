<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 29.05.2017
 * Time: 15:42
 */

namespace DocumentTemplates\Service;


interface DocumentServiceAwareInterface
{
    public function setDocumentService(DocumentService $service);
}