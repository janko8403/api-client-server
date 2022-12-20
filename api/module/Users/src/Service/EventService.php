<?php

namespace Users\Service;

use Commissions\Entity\Commission;
use Doctrine\Persistence\ObjectManager;
use Users\Entity\Event;
use Users\Entity\User;

class EventService
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
     * Creates user event.
     *
     * @param int      $type
     * @param int      $userId
     * @param string   $comment
     * @param int|null $creatingUserId
     * @param int|null $commissionId
     * @return Event
     */
    public function create(int    $type,
                           int    $userId,
                           string $comment,
                           ?int   $creatingUserId = null,
                           ?int   $commissionId = null
    ): Event
    {
        $event = new Event();
        $event->setType($type);
        $event->setUser($this->objectManager->find(User::class, $userId));
        $event->setComment($comment);
        if ($creatingUserId) $event->setCreatingUser($this->objectManager->find(User::class, $creatingUserId));
        if ($commissionId) $event->setCommission($this->objectManager->find(Commission::class, $commissionId));

        $this->objectManager->persist($event);
        $this->objectManager->flush();

        return $event;
    }
}