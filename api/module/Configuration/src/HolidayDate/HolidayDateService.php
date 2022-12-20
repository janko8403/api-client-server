<?php

namespace Configuration\HolidayDate;

use Configuration\Entity\HolidayDate;
use Configuration\Repository\HolidayDateRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;

class HolidayDateService
{
    public function __construct(
        private ObjectManager $objectManager,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function add(HolidayDate $holidayDate): void
    {
        /** @var HolidayDateRepository $holidayDateRepository */
        $holidayDateRepository = $this->objectManager->getRepository(HolidayDate::class);
        $found = $holidayDateRepository->findBy([
            'date' => $holidayDate->getDate(),
        ]);
        if (!empty($found)) {
            throw new \Exception("Holiday date {$holidayDate->getDate()->format('Y-m-d')} already exists");
        }
        $this->objectManager->persist($holidayDate);
        $this->objectManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function remove(int $holidayDateId): void
    {
        /** @var HolidayDate|null $holidayDate */
        $holidayDate = $this->objectManager->find(HolidayDate::class, $holidayDateId);
        if (is_null($holidayDate)) {
            throw new \Exception("Holiday date $holidayDateId not found");
        }
        $this->objectManager->remove($holidayDate);
        $this->objectManager->flush();
    }

    /**
     * @return array{string}
     */
    public function get(): array
    {
        /** @var EntityRepository $entityRepository */
        $entityRepository = $this->objectManager->getRepository(HolidayDate::class);
        return array_map(function (HolidayDate $holidayDate) {
            return $holidayDate->getDate()->format('Y-m-d');
        }, $entityRepository->findAll());
    }
}