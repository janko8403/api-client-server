<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 02.01.2017
 * Time: 11:31
 */

namespace Hr\RecordPicker;


use Doctrine\ORM\EntityManager;
use Hr\Entity\RecordPickerFilter;
use Users\Entity\User;
use Products\Repository\ProductRepository;

class RecordPickerService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var int
     */
    private $userId;

    /**
     * RecordPickerService constructor.
     *
     * @param EntityManager $entityManager
     * @param int           $userId
     */
    public function __construct(EntityManager $entityManager, int $userId)
    {
        $this->entityManager = $entityManager;
        $this->userId = $userId;
    }

    /**
     * Adds or removes provided id to/from a filter.
     *
     * @param string $filterId
     * @param string $action add|remove
     * @param int    $id     Selected record's id
     * @throws \Exception Thrown if unknown action is provided
     */
    public function save(string $filterId, string $action, int $id)
    {
        $record = $this->getOrCreateRecord($filterId);
        $selected = $record->getRecords();

        switch ($action) {
            case 'add':
                $selected[$id] = $id;
                break;
            case 'remove':
                unset($selected[$id]);
                break;
            default:
                throw new \Exception("Unknown action`$action`");
        }

        $record->setRecords($selected);
        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }

    public function saveAddOrClear(string $filterId, string $action, $repository, $params, $additionalParams = []): array
    {
        $record = $this->getOrCreateRecord($filterId);
        $selected = $record->getRecords();
        switch ($action) {
            case 'addAll':
                $selected = $repository->searchIds($additionalParams);
                break;
            case 'clearAll':
                $selected = [];
                break;
            case 'addSelected':
                $selected = $repository->searchIds($params);
                break;
            default:
                throw new \Exception("Unknow action `{$action}`");
        }

        $record->setRecords($selected);
        $this->entityManager->persist($record);
        $this->entityManager->flush();

        return ['count' => count($selected)];
    }

    /**
     * Sets filter to given record ids.
     *
     * @param string $filterId
     * @param array  $ids
     */
    public function setRecordIds(string $filterId, array $ids)
    {
        $temp = [];
        foreach ($ids as $id) {
            $temp[$id] = $id;
        }

        $record = $this->getOrCreateRecord($filterId);
        $record->setRecords($temp);

        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }

    public function clearFilter(int $userId, string $filterId)
    {
        $this->entityManager->getRepository(RecordPickerFilter::class)->clearFilter($userId, $filterId);
    }

    public function saveState(string $filterId)
    {
        $record = $this->getOrCreateRecord($filterId);
        $record->setPreviousRecords($record->getRecords() ?? []);
        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }

    public function cancelChanges(string $filterId)
    {
        $record = $this->getOrCreateRecord($filterId);
        $record->setRecords($record->getPreviousRecords());
        $record->setPreviousRecords([]);
        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }

    public function getRecordIds(string $filterId): array
    {
        $record = $this->getOrCreateRecord($filterId);

        return $record->getRecords();
    }

    /**
     * Gets record filter or creates new instance.
     *
     * @param string $filterId
     * @return RecordPickerFilter
     */
    private function getOrCreateRecord(string $filterId)
    {
        $record = $this->entityManager->getRepository(RecordPickerFilter::class)->findOneBy([
            'user' => $this->userId,
            'filterId' => $filterId,
        ]);
        if (!$record) {
            $record = new RecordPickerFilter();
            $record->setFilterId($filterId);
            $record->setUser($this->entityManager->find(User::class, $this->userId));
        }

        return $record;
    }
}