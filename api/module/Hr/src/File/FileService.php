<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.12.2016
 * Time: 20:06
 */

namespace Hr\File;

use Doctrine\ORM\EntityManager;
use Laminas\Filter\File\RenameUpload;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Http\Response as HttpResponse;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\IsImage;

class FileService
{
    const TYPE_MONITORING_THUMBNAIL = 'monitoringThumbnailsMandantPath';
    const TYPE_MONITORING_QUESTION_THUMBNAIL = 'monitoringQuestionThumbnailsMandantPath';
    const TYPE_MONITORING_QUESTION_ANSWER_PHOTO = 'monitoringQuestionAnswerPhotos';
    const TYPE_MONITORING_QUESTION_ANSWER_ATTACHMENT = 'monitoringQuestionAnswerAttachments';
    const TYPE_MONITORING_CREATED_DOCUMENTS = 'monitoringCreatedDocuments';
    const TYPE_COMMISSION_PARTNER_AGREEMENT = 'commissionPartnerAgreement';
    const TYPE_COMMISSION_PARTNER_TERMS = 'commissionPartnerTerms';
    const TYPE_COMMISSION_PARTNER_CONFIRMATION = 'commissionPartnerConfirmation';
    const TYPE_COMMISSION_PARTNER_AGREEMENT_CUSTOMER = 'commissionPartnerAgreementCustomer';
    const TYPE_COMMISSION_PARTNER_SUMMARY = 'commissionPartnerSummary';
    const TYPE_COMMISSION_MANAGER_CONFIRMATION = 'commissionManagerConfirmation';
    const TYPE_COMMISSION_PAYMENT = 'commissionPayment';
    const TYPE_COMMISSION_PARTNER_ANNEX = 'commissionAnnex';
    const TYPE_QUESTIONNAIRE_ATTACHMENTS = 'questionnaireAttachments';
    const TYPE_USER_COMPETENCES = 'competencesAttachments';
    const TYPE_DOCUMENT_USERS = 'documentsUser';
    const TYPE_AGREEMENT_FINISH_TERMS = 'agreementFinishTerms';
    const TYPE_AGREEMENT_FINISH_CERTIFICATE = 'agreementFinishCertificate';
    const TYPE_PAYER_WORK_CERTIFICATE = 'payerWorkCertificate';
    const TYPE_PAYER_LOGO = 'payerLogo';
    const TYPE_COMMISSION_50_PCT = 'commission50Pct';
    const TYPE_COMMISSION_DEFINITION_THUMBNAIL = 'commissionDefinitionThumbnail';
    const TYPE_COMMISSION_SUMMARY = 'commissionSummmary';
    const TYPE_COMMISSION_SUMMARY_FILE = 'commissionSummmaryFile';
    const TYPE_INVOICES = 'invoices';
    const TYPE_INVOICE_SUMMARIES = 'invoiceSummaries';
    const TYPE_INVOICE_SUMMARIES_XLS = 'invoiceSummariesXls';

    const MIME_IMPORT = ['text/plain', 'text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $config;

    /**
     * @var Repository
     */
    private $repository;

    public function __construct(EntityManager $entityManager, array $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    public static function getExtensionForMimeType(string $mimeType)
    {
        switch ($mimeType) {
            case 'image/png':
                return 'png';
            case 'image/jpeg':
                return 'jpg';
            case 'application/pdf':
                return 'pdf';
            case 'application/msword':
                return 'doc';
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'docx';
            case 'application/vnd.ms-excel':
                return 'xls';
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'xlsx';
            case 'text/plain':
                return 'txt';
            default:
                return null;
        }
    }

    /**
     * Uploads a file.
     *
     * @param array  $file             $_FILES array
     * @param string $type             Category of file to upload ex. monitoringThumbnailsMandantPath. Paths are stored in a config file under key `paths`.
     * @param array  $replaceInPath    Data to replace placeholders in path
     * @param bool   $preserveFilename Preservers original filename (adds random string)
     * @param bool   $returnFullPath   Return full file path or only filename
     * @return string Uploaded file's name
     * @throws \Exception
     */
    public function upload(
        array  $file,
        string $type,
        array  $replaceInPath = [],
        bool   $preserveFilename = false,
        bool   $returnFullPath = false
    ): string
    {
        $path = $this->getPath($type, $replaceInPath);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if ($preserveFilename) {
            $path .= '/' . $file['Filedata']['name'];
        }

        $filterUpload = new RenameUpload([
            'target' => $path,
            'randomize' => true,
        ]);

        $uploadedFile = $filterUpload->filter($file['Filedata']);

        if ($returnFullPath) {
            return $uploadedFile['tmp_name'];
        } else {
            $uploadedFileName = pathinfo(
                is_array($uploadedFile) ? $uploadedFile['tmp_name'] : $uploadedFile,
                PATHINFO_FILENAME
            );

            return $uploadedFileName;
        }
    }

    /**
     * Uploads a file to temp dir.
     *
     * @param array $file $_FILES array
     * @return string Uploaded file's temp path
     */
    public function uploadTemp(array $file)
    {
        $filterUpload = new RenameUpload([
            'randomize' => true,
        ]);

        // pre upload validation
        $validatorFileSize = new FilesSize(['max' => '20MB']);
        $validatorIsImage = new IsImage();

        if ($validatorFileSize->isValid($file)) {
            // rename file
            $uploadedFile = $filterUpload->filter($file['Filedata']);
            $filename = $uploadedFile['tmp_name'];

            // post upload validation
            if (!file_exists($filename)) {
                return false;
            }

            if ($validatorIsImage->isValid($file['Filedata']['tmp_name'])) {
                // images
                if (@imagecreatefromjpeg($filename) !== false) {
                    return $uploadedFile['tmp_name'];
                } else {
                    return false;
                }
            } else {
                // other types
                return $uploadedFile['tmp_name'];
            }
        }

        return false;
    }

    /**
     * Gets path of given type. Replaces placeholders in path with passed params.
     *
     * @param string $type
     * @param array  $replaceInPath
     * @return string
     * @throws \Exception Thrown if path of $type doesn't exist
     */
    public function getPath(string $type, array $replaceInPath = []): string
    {
        if (empty($this->config[$type])) {
            throw new \Exception("Path `$type` doesn't exist.");
        }

        $path = $this->config[$type];

        foreach ($replaceInPath as $k => $v) {
            $path = str_replace('$' . $k . '$', $v, $path);
        }

        return $path;
    }

    /**
     * Return a Response object serving a file.
     *
     * @param string      $fileName
     * @param string|null $displayName
     * @return Response
     */
    public function serveFile(string $fileName, string $displayName = null): Response
    {
        $response = new Response();
        if (!file_exists($fileName)) {
            $response->setStatusCode(HttpResponse::STATUS_CODE_404);
            return $response;
        }
        $file = file_get_contents($fileName);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fileName);

        $response->setContent($file);
        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', $mime);

        if ($displayName) {
            $fileinfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $fileinfo->file($fileName);
            $ext = self::getExtensionForMimeType($mime);

            if ($ext) {
                $displayName .= ".$ext";
            }

            $response
                ->getHeaders()
                ->addHeaderLine('Content-Disposition: attachment; filename=' . $displayName);
        }

        return $response;
    }

    public function getFilePath($type, $id, $replace = [], $repository = null)
    {
        if ($repository) {
            $this->repository = $repository;
        }

        $path = $this->getPath($type, $replace);

        switch ($type) {
            case self::TYPE_MONITORING_QUESTION_ANSWER_PHOTO:
                return '';
            case self::TYPE_MONITORING_QUESTION_THUMBNAIL:
            case self::TYPE_MONITORING_THUMBNAIL:
            case self::TYPE_COMMISSION_DEFINITION_THUMBNAIL:
                $name = $this->repository->find($id)->getThumbnail();
                $fileName = $path . '/' . $name;
                break;
            case self::TYPE_PAYER_LOGO:
                $name = $this->repository->find($id)->getLogo();
                $fileName = $path . '/' . $name;
                break;
            case self::TYPE_QUESTIONNAIRE_ATTACHMENTS:
            case self::TYPE_USER_COMPETENCES:
                $name = $this->repository->find($id)->getValue();
                $fileName = $path . '/' . $name;
                break;
            case self::TYPE_COMMISSION_SUMMARY:
                $name = $this->repository->find($id)->getSummaryFile();
                $fileName = $path . '/' . $name;
                break;
            case self::TYPE_COMMISSION_SUMMARY_FILE:
                $name = $this->repository->find($id)->getName();
                $fileName = $path . '/' . $name;
                break;
            case self::TYPE_COMMISSION_PARTNER_TERMS:
                $name = $this->repository->find($id)->getPartnerTermsFile();
                $fileName = $path . '/' . $name;
                break;
            case self::TYPE_MONITORING_QUESTION_ANSWER_ATTACHMENT:
                $fileName = $path . '/' . $id['fulfillmentId'] . '/' . $id['answerId'];
                break;
            default:
                throw new \Exception("Path `$type` doesn't exist.");
        }

        return $fileName;
    }

    public function showFile(string $type, $_repository, int $id, array $replace = []): Response
    {
        $this->repository = $_repository;

        $fileName = $this->getFilePath($type, $id, $replace);

        $file = file_get_contents($fileName);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fileName);

        $response = new Response();
        $response->setContent($file);
        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', $mime);

        return $response;
    }

    /**
     * Validates provided filename's mime type.
     *
     * @param string $filename
     * @param mixed  $type MIME type(s) to validate against
     * @return bool
     */
    public function validateType(string $filename, $type): bool
    {
        $types = (array)$type;

        $fileinfo = new \finfo(FILEINFO_MIME_TYPE);

        return in_array($fileinfo->file($filename), $types);
    }

    public function downloadFromString(string $fileContent, string $displayName, string $mimeType = 'application/pdf')
    {
        $response = new \Laminas\Http\Response();
        $response->setContent($fileContent);
        $response
            ->getHeaders()->clearHeaders()
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $displayName . '.pdf"')
            ->addHeaderLine('Content-Length', strlen($fileContent))
            ->addHeaderLine('Content-Type', $mimeType);

        return $response;
    }

    /**
     * Check wheather file exists in a path of given type.
     *
     * @param string $type
     * @param string $filename
     * @param array  $replace
     * @return bool
     * @throws \Exception
     */
    public function fileExists(string $type, string $filename, array $replace): bool
    {
        $path = $this->getPath($type, $replace);

        return file_exists($path . DIRECTORY_SEPARATOR . $filename);
    }
}