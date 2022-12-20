<?php


namespace Notifications\SmsCallback;


use Commissions\Entity\CommissionUser;
use Commissions\Service\CommisionService;
use Commissions\Service\GradeService;

class Rate implements CallbackInterface
{
    /**
     * @var GradeService
     */
    private $gradeService;

    /**
     * Rate constructor.
     * @param GradeService $gradeService
     */
    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function process(CommissionUser $commissionUser, string $text): void
    {
        if (empty($commissionUser->getRating())) {
            $grade = (int)substr($text, 0, 1);
            if ($grade >= 1 && $grade <= 5) {
                $comment = trim(substr($text, 1));
                $userId = $commissionUser->getCommission()->getCreatingUser()->getId();
                $this->gradeService->rate($commissionUser->getCommission(), $userId, $grade, $comment);
            }
        }
    }
}