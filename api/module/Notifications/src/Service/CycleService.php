<?php

namespace Notifications\Service;

use Doctrine\Persistence\ObjectManager;
use Notifications\Entity\Cycle;

class CycleService
{

    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function fetchAll(): array
    {
        return $this->objectManager->getRepository(Cycle::class)->findAll();
    }

    public function save(Cycle $cycle): Cycle
    {
        $this->objectManager->persist($cycle);
        $this->objectManager->flush();

        return $cycle;
    }

    public function find(int $id): ?Cycle
    {
        return $this->objectManager->find(Cycle::class, $id);
    }
}