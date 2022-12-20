<?php


namespace Notifications\SmsCallback;


use Commissions\Entity\CommissionUser;
use Commissions\Entity\PresenceConfirmation as PresenceConfirmationEntity;
use Doctrine\Persistence\ObjectManager;

class PresenceConfirmation implements CallbackInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * PresenceConfirmation constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function process(CommissionUser $commissionUser, string $text): void
    {
        $answer = strtoupper(substr(trim($text), 0, 1));

        $presenceConfirmation = $this->objectManager->getRepository(PresenceConfirmationEntity::class)
            ->findOneBy(['commissionUser' => $commissionUser->getId(), 'answer' => null]);
        if ($presenceConfirmation) {
            $presenceConfirmation->setAnswer($answer == 'T');
            $this->objectManager->persist($presenceConfirmation);
            $this->objectManager->flush();
        }
    }

}