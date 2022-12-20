<?php
namespace NpsRating\Service;

use Doctrine\Persistence\ObjectManager;
use NpsRating\Entity\NpsRating;

class NpsRatingClearService
{
    public function __construct(private ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function clearAll()
    {
        $this->objectManager->getRepository(NpsRating::class)->deleteAllNpsRating();
        $this->objectManager->flush();
    }
}