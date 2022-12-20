<?php


namespace DocumentTemplates\Replacer;


use Commissions\Entity\CommissionUser;
use Commissions\Service\CommissionFetchService;
use DocumentTemplates\Entity\DocumentTemplate;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

class WorkCertificate implements ReplacerInterface
{
    /**
     * @var CommissionFetchService
     */
    private $fetchService;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * WorkCertificate constructor.
     * @param CommissionFetchService $fetchService
     * @param PhpRenderer $renderer
     */
    public function __construct(CommissionFetchService $fetchService, PhpRenderer $renderer)
    {
        $this->fetchService = $fetchService;
        $this->renderer = $renderer;
    }

    public function prepare(array $params): array
    {
        if (empty($params['userId'])) {
            throw new \Exception("Parameter userId not provided");
        }
        if (empty($params['dateFrom'])) {
            throw new \Exception("Parameter dateFrom not provided");
        }
        if (empty($params['dateTo'])) {
            throw new \Exception("Parameter dateTo not provided");
        }

        $tags = array_map(function($e) {
            return "[$e]";
        }, [
            DocumentTemplate::TAG_DATE_FROM,
            DocumentTemplate::TAG_DATE_TO,
            DocumentTemplate::TAG_AGREEMENT_HISTORY_BY_CUSTOMER
        ]);

        $commissions = $this->fetchService->getWorkedInDates(
            $params['userId'],
            $params['dateFrom'],
            $params['dateTo']
        );

        return [
            'from' => $tags,
            'to' => [
                $params['dateFrom'], // TAG_DATE_FROM
                $params['dateTo'], // TAG_DATE_TO
                $this->getCommissionHistory($commissions), // TAG_AGREEMENT_HISTORY_BY_CUSTOMER
            ]
        ];
    }

    private function getCommissionHistory(array $commissions): string
    {
        $data = [];

        /** @var CommissionUser $cu */
        foreach ($commissions as $cu) {
            $customer = $cu->getCommission()->getCustomer();

            $data[$customer->getActiveData()->getName()][] = [
                'date' => $cu->getCommission()->getStartDate()->format('Y-m-d H:i'),
                'hours' => $cu->getCommission()->getHours(),
            ];
        }

        $vm = new ViewModel(['data' => $data]);
        $vm->setTerminal(true);
        $vm->setTemplate('commissions/agreements/history-by-customer');

        return $this->renderer->render($vm);
    }
}