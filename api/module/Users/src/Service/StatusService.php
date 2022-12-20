<?php

namespace Users\Service;

use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;
use Users\Entity\UserDataVersion;

class StatusService
{
    private ObjectManager $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Sets user status after feature update.
     *
     * @param int $userId
     */
    public function featureUpdate(int $userId): void
    {
        $user = $this->objectManager->find(User::class, $userId);
        $status = in_array($user->getProcessStatus(), [User::PROCESS_READY, User::PROCESS_COMPETENCE])
            ? User::PROCESS_COMPETENCE
            : User::PROCESS_COMPLETE;
        $this->setProcessStatus($user, $status);;
    }

    /**
     * Sets user status after feature confirmation.
     *
     * @param int  $userId
     * @param bool $hasUnconfirmed
     */
    public function confirmFeature(int $userId, bool $hasUnconfirmed)
    {
        $user = $this->objectManager->find(User::class, $userId);

        if ($user->getProcessStatus() >= User::PROCESS_COMPETENCE) {
            $status = $hasUnconfirmed ? User::PROCESS_COMPETENCE : User::PROCESS_READY;
            $this->setProcessStatus($user, $status);
        }
    }

    /**
     * Sets user status based on user data version (active contract yes/no).
     *
     * @param int $userId
     * @param int $dataVersionStatus
     */
    public function setBasedOnDataVersionStatus(int $userId, int $dataVersionStatus): void
    {
        $user = $this->objectManager->find(User::class, $userId);
        $status = $dataVersionStatus == UserDataVersion::STATUS_CONTRACT ? User::PROCESS_READY : User::PROCESS_COMPLETE;
        $this->setProcessStatus($user, $status);
    }

    /**
     * Sets user status.
     *
     * @param User $user
     * @param int  $status
     */
    private function setProcessStatus(User $user, int $status): void
    {
        $user->setProcessStatus($status);
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
}