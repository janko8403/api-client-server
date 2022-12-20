<?php


namespace DocumentTemplates\Replacer;


use Commissions\Entity\CommissionUser;
use DocumentTemplates\Entity\DocumentTemplate;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

class CommissionSummary implements ReplacerInterface
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * CommissionSummary constructor.
     * @param PhpRenderer $renderer
     */
    public function __construct(PhpRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function prepare(array $params): array
    {
        if (empty($params['summary'])) {
            throw new \Exception("Parameter summary not provided");
        }

        $tags = array_map(function($e) {
            return "[$e]";
        }, [
            DocumentTemplate::TAG_SUMMARY_HISTORY,
            DocumentTemplate::TAG_SUMMARY_TOTAL,
        ]);

        $summary = $params['summary'];
        return [
            'from' => $tags,
            'to' => [
                $this->getCommissionHistory($summary->getCompletedCommissionUsers()), // TAG_SUMMARY_HISTORY
                $summary->getTotal(), // TAG_SUMMARY_TOTAL
            ]
        ];
    }

    private function getCommissionHistory($commissions): string
    {
        $data = [];

        /** @var CommissionUser $cu */
        foreach ($commissions as $cu) {
            $customer = $cu->getCommission()->getCustomer();

            $data[] = [
                'customer' => $customer->getActiveData()->getName(),
                'date' => $cu->getCommission()->getStartDate()->format('Y-m-d H:i'),
                'hours' => $cu->getCommission()->getHours(),
                'rate' => $cu->getRate()->getNetEmployee(),
                'position' => $cu->getCommission()->getDefinition()->getName(),
            ];
        }

        $vm = new ViewModel(['data' => $data]);
        $vm->setTerminal(true);
        $vm->setTemplate('commissions/summaries/history');

        return $this->renderer->render($vm);
    }
}