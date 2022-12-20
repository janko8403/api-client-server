<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 29.05.2017
 * Time: 15:41
 */

namespace DocumentTemplates\Service;

use Customers\Entity\Customer;
use Customers\Entity\CustomerData;
use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\Document;
use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Entity\DocumentTemplate as DT;
use Monitorings\Entity\MonitoringQuestion;
use Monitorings\Entity\MonitoringQuestionType;
use Monitorings\Entity\MonitoringUserFulfillment;
use Monitorings\Entity\MonitoringUserFulfillmentQuestion;
use Monitorings\Entity\MonitoringUserFulfillmentQuestionAnswer;
use Payers\Entity\Payer;
use Payers\Entity\PayerData;
use Hr\Content\PdfService;
use Hr\Entity\DictionaryDetailsDescription;
use Hr\Entity\RegionSubregionJoint;
use Hr\File\FileService;
use Users\Entity\User;
use Laminas\Http\Response;

class DocumentService
{
    /**
     * @var
     */
    private $pdfService;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var User
     */
    private $user;

    /**
     * DocumentService constructor.
     *
     * @param ObjectManager $objectManager
     * @param PdfService    $pdfService
     * @param FileService   $fileService
     */
    public function __construct(ObjectManager $objectManager, PdfService $pdfService, FileService $fileService, int $userId)
    {
        $this->pdfService = $pdfService;
        $this->fileService = $fileService;
        $this->objectManager = $objectManager;
        $this->user = $objectManager->find(User::class, $userId);
    }

    /**
     * Generates document from user fulfilment.
     *
     * @param MonitoringUserFulfillment $fulfillment
     * @param DocumentTemplate          $template
     */
    public function generate(MonitoringUserFulfillment $fulfillment, DocumentTemplate $template)
    {
        $content = $this->generateContent($fulfillment, $template);
        $path = $this->fileService->getPath(FileService::TYPE_MONITORING_CREATED_DOCUMENTS);

        // add a record
        $document = new Document();
        $document->setTemplate($template);
        $document->setFulfilment($fulfillment);
        $document->setPayer(($fulfillment->getCustomer()->getPayer()) ? ($fulfillment->getCustomer()->getPayer()) : new Payer());
        $document->setUser($this->user);

        $this->objectManager->persist($document);
        $this->objectManager->flush();

        $document->setFilename($document->getId() . '.pdf');

        $this->objectManager->persist($document);
        $this->objectManager->flush();

        // generate file
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->pdfService->generate(
            $content['body'],
            sprintf('%s/%s.pdf', $path, $document->getId()),
            'F',
            $content['header'],
            $content['footer']
        );
    }

    /**
     * Generates document part.
     *
     * @param MonitoringUserFulfillment $fulfillment
     * @param DocumentTemplate          $template
     * @return array
     */
    public function generateContent(MonitoringUserFulfillment $fulfillment, DocumentTemplate $template): array
    {
        $payer = null;
        /** @var PayerData $payer */
        if ($fulfillment->getCustomer()->getPayer()) {
            $payer = $fulfillment->getCustomer()->getPayer()->getActiveData();
        }

        /** @var CustomerData $customerData */
        $customerData = $fulfillment->getCustomer()->getActiveData();

        /**
         * @var Customer
         */
        $customer = $fulfillment->getCustomer();
        if ($customer->getRegion()) {
            $subregion = $this->objectManager->getRepository(RegionSubregionJoint::class)
                ->getSubregionForRegion($customer->getRegion()->getId());

            if ($subregion) {
                $detailsDescription = $this->objectManager->getRepository(DictionaryDetailsDescription::class)
                    ->getDesctiptionForDetails($subregion);
            }
        }

        $payerSelectedClients = "";

        /** @var MonitoringUserFulfillmentQuestion $fulfillmentQuestions */
        $fulfillmentQuestions = $this->objectManager->getRepository(MonitoringUserFulfillmentQuestion::class)->findBy([
            'fulfilment' => $fulfillment,
        ]);

        /** @var MonitoringQuestion $question */
        foreach ($fulfillmentQuestions as $mufQuestion) {

            $question = $mufQuestion->getQuestion();
            if ($question->getType()->getKey() == MonitoringQuestionType::TYPE_LOCATIONS_LIST) {

                $fulfillmentAnswers = $this->objectManager->getRepository(MonitoringUserFulfillmentQuestionAnswer::class)
                    ->getAnswersForFulfillmentQuestion($fulfillment, $question);

                /** @var MonitoringUserFulfillmentQuestionAnswer $answer */
                foreach ($fulfillmentAnswers as $answer) {
                    $payerSelectedClients .= $answer->getCustomer()->getActiveData()->getName() . ", " . $answer->getCustomer()->getActiveData()->getFullAddress() . "<br />";
                }
            }
        }


        $replaceFrom = [
            '[' . DT::TAG_CURRENT_DATE . ']',
            '[' . DT::TAG_CURRENT_HOUR . ']',

            '[' . DT::TAG_PAYER_NAME . ']',
            '[' . DT::TAG_PAYER_STREET . ']',
            '[' . DT::TAG_PAYER_STREET_NUMBER . ']',
            '[' . DT::TAG_PAYER_STREET_PREFIX . ']',
            '[' . DT::TAG_PAYER_LOCAL . ']',
            '[' . DT::TAG_PAYER_ZIPCODE . ']',
            '[' . DT::TAG_PAYER_CITY . ']',

            '[' . DT::TAG_CUSTOMER_NAME . ']',
            '[' . DT::TAG_CUSTOMER_STREET . ']',
            '[' . DT::TAG_CUSTOMER_STREET_NUMBER . ']',
            '[' . DT::TAG_CUSTOMER_LOCAL . ']',
            '[' . DT::TAG_CUSTOMER_ZIPCODE . ']',
            '[' . DT::TAG_CUSTOMER_CITY . ']',

            '[' . DT::TAG_SUBCHAIN_VARIABLE1 . ']',
            '[' . DT::TAG_SUBCHAIN_VARIABLE2 . ']',
            '[' . DT::TAG_SUBCHAIN_VARIABLE3 . ']',
            '[' . DT::TAG_SUBCHAIN_VARIABLE4 . ']',
            '[' . DT::TAG_SUBCHAIN_VARIABLE5 . ']',
            '[' . DT::TAG_SUBCHAIN_VARIABLE6 . ']',

            '[' . DT::TAG_SUBREGION_VARIABLE1 . ']',
            '[' . DT::TAG_SUBREGION_VARIABLE2 . ']',
            '[' . DT::TAG_SUBREGION_VARIABLE3 . ']',
            '[' . DT::TAG_SUBREGION_VARIABLE4 . ']',
            '[' . DT::TAG_SUBREGION_VARIABLE5 . ']',
            '[' . DT::TAG_SUBREGION_VARIABLE6 . ']',

            '[' . DT::TAG_PAYER_SELECTED_CLIENTS . ']',
            '[' . DT::TAG_PAYER_ACTIVE_CLIENTS . ']',
        ];

        $replaceTo = [
            date('Y-m-d'), // TAG_CURRENT_DATE
            date('H:i'), // TAG_CURRENT_HOUR

            (!$payer) ? '' : $payer->getName(), // TAG_PAYER_NAME
            (!$payer) ? '' : $payer->getStreetName(), // TAG_PAYER_STREET
            (!$payer) ? '' : $payer->getStreetNumber(), // TAG_PAYER_STREET_NUMBER
            (!$payer) ? '' : $payer->getStreetPrefix(), // TAG_PAYER_STREET_PREFIX
            (!$payer) ? '' : $payer->getLocalNumber(), // TAG_PAYER_LOCAL
            (!$payer) ? '' : $payer->getZipCode(), // TAG_PAYER_ZIPCODE
            (!$payer) ? '' : $payer->getCity()->getName(), // TAG_PAYER_CITY

            $customerData->getName(), // TAG_CUSTOMER_NAME
            $customerData->getStreetName(), // TAG_CUSTOMER_STREET
            $customerData->getStreetNumber(), // TAG_CUSTOMER_STREET_NUMBER
            $customerData->getLocalNumber(), // TAG_CUSTOMER_LOCAL
            $customerData->getZipCode(), // TAG_CUSTOMER_ZIPCODE
            (!$customerData->getCity()) ? '' : $customerData->getCity()->getName(), // TAG_CUSTOMER_CITY

            (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getVariable1(),  //subchain variables
            (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getVariable2(),
            (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getVariable3(),
            (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getVariable4(),
            (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getVariable5(),
            (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getVariable6(),
            //subformat
            (!isset($detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_1])) ? '' : $detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_1],
            (!isset($detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_2])) ? '' : $detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_2],
            (!isset($detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_3])) ? '' : $detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_3],
            (!isset($detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_4])) ? '' : $detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_4],
            (!isset($detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_5])) ? '' : $detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_5],
            (!isset($detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_6])) ? '' : $detailsDescription[DictionaryDetailsDescription::KEY_VARIABLE_6],


            $payerSelectedClients, // TAG_PAYER_SELECTED_CLIENTS
            '[' . DT::TAG_PAYER_ACTIVE_CLIENTS . ']', // TAG_PAYER_ACTIVE_CLIENTS
        ];

        return [
            'header' => str_replace($replaceFrom, $replaceTo, $template->getContentHeader()),
            'body' => str_replace($replaceFrom, $replaceTo, $template->getContentBody()),
            'footer' => str_replace($replaceFrom, $replaceTo, $template->getContentFooter()),
        ];
    }

    /**
     * Downloads generated document
     *
     * @param Document $document
     * @return Response
     */
    public function download(Document $document)
    {
        $path = $this->fileService->getPath(FileService::TYPE_MONITORING_CREATED_DOCUMENTS);
        $filename = sprintf('%s/%s.pdf', $path, $document->getId());
        $name = $document->getTemplate()->getName();

        $response = new Response();
        $response->setContent(file_get_contents($filename));
        $response
            ->getHeaders()->clearHeaders()
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $name . '.pdf"')
            ->addHeaderLine('Content-Length', filesize($filename))
            ->addHeaderLine('Content-Type', 'application/pdf');

        return $response;
    }
}