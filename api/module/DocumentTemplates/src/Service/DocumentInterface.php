<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.11.2017
 * Time: 17:18
 */

namespace DocumentTemplates\Service;

use DocumentTemplates\Entity\DocumentTemplate;

interface DocumentInterface
{
    public function generate(array $params);

    public function generateContent(DocumentTemplate $documentTemplate, array $params) : array;

    public function findDocumentTemplate($instance);

    public function download(string $type, string $filename, string $displayName, array $params = []);
}