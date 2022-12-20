<?php

namespace ApiNpsRatings\V1\Service;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ObjectManager;
use NpsRating\Entity\NpsRating;
use Users\Entity\User;

class NpsRatingService
{

    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function fetchAllForUser(User $user): Query
    {
        return $this->objectManager->getRepository(NpsRating::class)->fetchAllForUser($user);
    }

    public function fetchForUser(int $id, User $user): ?NpsRating
    {
        return $this->objectManager->getRepository(NpsRating::class)->fetchForUser($id, $user);
    }
}