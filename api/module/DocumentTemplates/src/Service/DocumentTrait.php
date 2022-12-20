<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.11.2017
 * Time: 17:15
 */

namespace DocumentTemplates\Service;

use Doctrine\Persistence\ObjectManager;
use Hr\Content\PdfService;
use Hr\File\FileService;

trait DocumentTrait
{
    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var PdfService
     */
    protected $pdfService;
}