<?php


namespace DocumentTemplates\Replacer;


use Commissions\Entity\Agreement;
use Commissions\Service\AgreementService;
use DocumentTemplates\Entity\DocumentTemplate;

class UserAgreement implements ReplacerInterface
{
    /**
     * @var AgreementService
     */
    private $agreementService;

    /**
     * UserAgreement constructor.
     * @param AgreementService $agreementService
     */
    public function __construct(AgreementService $agreementService)
    {
        $this->agreementService = $agreementService;
    }

    public function prepare(array $params): array
    {
        $tags = array_map(function($e) {
            return "[$e]";
        }, [
            DocumentTemplate::TAG_AGREEMENT_START_DATE => 'data rozpoczęcia umowy',
            DocumentTemplate::TAG_AGREEMENT_END_DATE => 'data wygaśnięcia umowy',
            DocumentTemplate::TAG_AGREEMENT_HISTORY => 'historia zleceń w ramach umowy',
            DocumentTemplate::TAG_AGREEMENT_NET_TOTAL_EMPLOYEE => 'suma netto w ramach umowy',
            DocumentTemplate::TAG_AGREEMENT_NUMBER => 'numer umowy',
            DocumentTemplate::TAG_AGREEMENT_HISTORY_BY_CUSTOMER => 'historia zleceń po kliencie',
            DocumentTemplate::TAG_AGREEMENT_HISTORY_BY_DATE => 'historia zleceń chronologicznie',
        ]);

        $commissionUser = $params['commission_user'];

        /** @var Agreement $agreement */
        $agreement = $params['agreement'] ?? $commissionUser->getUser()->getActiveAgreement();
        $startDate = $this->agreementService->getDateOfFirstCommission($agreement);

        return [
            'from' => $tags,
            'to' => [
                $startDate ? $startDate->format('Y-m-d') : '', // TAG_AGREEMENT_START_DATE
                $agreement ? (
                $agreement->getTerminationDate()
                    ? $agreement->getTerminationDate()->format('Y-m-d')
                    : $agreement->getEndDate()->format('Y-m-d')
                ) : '', // TAG_AGREEMENT_END_DATE
                $agreement ? $this->agreementService->generateHistory($agreement) : '', // TAG_AGREEMENT_HISTORY
                $agreement ? $this->agreementService->getNetTotalEmployee($agreement) : 0, //TAG_AGREEMENT_NET_TOTAL_EMPLOYEE
                $agreement ? $agreement->getNumber() : '', // TAG_AGREEMENT_NUMBER
                $agreement ? $this->agreementService->generateHistoryByCustomer($agreement) : '', // TAG_AGREEMENT_HISTORY_BY_CUSTOMER
                $agreement ? $this->agreementService->generateHistoryByDate($agreement) : '', // TAG_AGREEMENT_HISTORY_BY_DATE
            ]
        ];
    }

}