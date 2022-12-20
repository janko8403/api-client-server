<?php


namespace DocumentTemplates\Replacer;


use Commissions\Entity\Definition;
use Commissions\Service\CommisionService;
use Customers\Entity\CustomerData;
use DocumentTemplates\Entity\DocumentTemplate;
use Monitorings\Entity\Monitoring;

class Comission implements ReplacerInterface
{
    /**
     * @var CommisionService
     */
    private $commisionService;

    /**
     * Comission constructor.
     * @param CommisionService $commisionService
     */
    public function __construct(CommisionService $commisionService)
    {
        $this->commisionService = $commisionService;
    }

    public function prepare(array $params): array
    {
        $tags = array_map(function($e) {
            return "[$e]";
        }, [
            DocumentTemplate::TAG_COMMISSION_DATE => 'data zlecenia',
            DocumentTemplate::TAG_COMMISSION_TIME => 'czas zlecenia',
            DocumentTemplate::TAG_COMMISSION_HOURS => 'godziny zlecenia',
            DocumentTemplate::TAG_COMMISSION_AMOUNT => 'łączna stawka zlecenia',
            DocumentTemplate::TAG_COMMISSION_CUSTOMER => 'nazwa zleceniodawcy',
            DocumentTemplate::TAG_COMMISSION_NAME => 'nazwa zlecenia',
            DocumentTemplate::TAG_COMMISSION_DESCRIPTION => 'opis zlecenia',
            DocumentTemplate::TAG_COMMISSION_COMPETENCES => 'lista kompetencji zlecenia',
            DocumentTemplate::TAG_COMMISSION_ACCEPT_DATE => 'data akceptacji zlecenia',
            DocumentTemplate::TAG_COMMISSION_AGREEMENT_ID => 'ID umowy',
            DocumentTemplate::TAG_COMMISSION_RATING => 'ocena zlecenia',
            DocumentTemplate::TAG_COMMISSION_AMOUNT_WITH_PERCENTAGE => 'kwota zlecenia z prowizją',
        ]);

        $commission = $params['commission'];
        $commissionUser = $params['commission_user'];
        /** @var CustomerData $customerData */
        $customerData = $commissionUser->getCommission()
            ? $commissionUser->getCommission()->getCustomer()->getActiveData()
            : new CustomerData();
        $definition = $commission->getDefinition() ?? new Definition();
        $amountWithPercentage = $commission->getCurrentGrossRateTotal();

        return [
            'from' => $tags,
            'to' => [
                $commission->getStartDate() ? $commission->getStartDate()->format('Y-m-d') : '', // TAG_COMMISSION_DATE
                $commission->getStartDate() ? $commission->getStartDate()->format('H:i') . ' - ' . $commission->getEndDate()->format('H:i') : '', // TAG_COMMISSION_TIME
                $commission->getHours(), // TAG_COMMISSION_HOURS
                $commission->getHours() ? $commissionUser->getTotalRate() : '', // TAG_COMMISSION_AMOUNT
                $customerData->getName(), // TAG_COMMISSION_CUSTOMER
                $definition->getName(), // TAG_COMMISSION_NAME
                $definition->getThumbnailDescription(), // TAG_COMMISSION_DESCRIPTION
                implode(', ', $this->commisionService->getFeatureNamesForCommission($commission)), // TAG_COMMISSION_COMPETENCES
                $commissionUser->getAcceptDate() ? $commissionUser->getAcceptDate()->format('Y-m-d H:i:s') : '', // TAG_COMMISSION_ACCEPT_DATE
                $commissionUser->getAgreementId(), // TAG_COMMISSION_AGREEMENT_ID
                $commissionUser->getRating(), // TAG_COMMISSION_RATING
                $amountWithPercentage, // TAG_COMMISSION_AMOUNT_WITH_PERCENTAGE
            ]
        ];
    }

}