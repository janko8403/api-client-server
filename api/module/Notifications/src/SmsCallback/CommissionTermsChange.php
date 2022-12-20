<?php


namespace Notifications\SmsCallback;


use Commissions\Entity\CommissionUser;
use Commissions\Entity\TermsChange;
use CommissionsManagement\Service\AnnexService;
use Doctrine\Persistence\ObjectManager;

class CommissionTermsChange implements CallbackInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var AnnexService
     */
    private $service;

    /**
     * PresenceConfirmation constructor.
     *
     * @param ObjectManager $objectManager
     * @param AnnexService  $service
     */
    public function __construct(ObjectManager $objectManager, AnnexService $service)
    {
        $this->objectManager = $objectManager;
        $this->service = $service;
    }

    public function process(CommissionUser $commissionUser, string $text): void
    {
        $answer = strtoupper(substr(trim($text), 0, 1));

        $change = $this->objectManager->getRepository(TermsChange::class)
            ->findOneBy(['commissionUser' => $commissionUser->getId(), 'answer' => null]);
        if ($change && empty($change->getAnswer())) {
            $change->setAnswer($answer == 'T');
            $this->objectManager->persist($change);
            $this->objectManager->flush();

            $this->service->confirm($change);
            $this->service->setBlockPaymentStatus($change->getCommissionUser(), false);
        }
    }
}