<?php

namespace AssemblyOrders\Repository;

use ApiAssemblyOrders\V1\Service\AssemblyOrderService;
use AssemblyOrders\Entity\AssemblyOrder;
use AssemblyOrders\Entity\AssemblyOrderUser;
use Doctrine\ORM\Query;
use Hr\Repository\BaseRepository;
use Hr\Repository\SearchableInterface;
use Users\Entity\User;

class AssemblyOrderRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('o');
        $qb->select('o', 'ou', 'u');
        $qb->leftJoin('o.users', 'ou');
        $qb->leftJoin('ou.user', 'u');

        if (!empty($data['assembly'])) {
            $qb->andWhere($qb->expr()->orX(
                'o.installationName like :assembly',
                'o.installationPhoneNumber like :assembly',
                'o.installationEmail like :assembly',
                'o.installationCity like :assembly',
                'o.installationAddress like :assembly'
            ))->setParameter('assembly', "%$data[assembly]%");
        }
        if (!empty($data['user'])) {
            $qb->andWhere('u.id = :uid')->setParameter('uid', $data['user']);
        }
        if (!empty($data['customer'])) {
            $qb->andWhere('o.customer = :customer')->setParameter('customer', $data['customer']);
        }
        if (!empty($data['measurementStatus'])) {
            $qb->andWhere('o.measurementStatus = :measurementStatus')->setParameter('measurementStatus', $data['measurementStatus']);
        }
        if (!empty($data['installationStatus'])) {
            $qb->andWhere('o.installationStatus = :installationStatus')->setParameter('installationStatus', $data['installationStatus']);
        }
        if (!empty($data['installationStatus'])) {
            $qb->andWhere('o.installationStatus = :installationStatus')->setParameter('installationStatus', $data['installationStatus']);
        }
        if (!empty($data['dateFrom'])) {
            $qb->andWhere('date(o.expectedInstallationDateTime) >= :dateFrom')->setParameter('dateFrom', $data['dateFrom']);
        }
        if (!empty($data['dateTo'])) {
            $qb->andWhere('date(o.expectedInstallationDateTime) <= :dateTo')->setParameter('dateTo', $data['dateTo']);
        }
        if (!empty($data['orderId'])) {
            $qb->andWhere('o.idMeasurementOrder like :orderId or o.idInstallationOrder like :orderId')->setParameter('orderId', "%$data[orderId]%");
        }
        if (!empty($data['accepted'])) {
            if ($data['accepted'] == '1') $qb->andWhere('ou.id is not null');
            elseif ($data['accepted'] == '2') $qb->andWhere('ou.id is null');
        }

        if (!empty($data['customers'])) {
            $qb->andWhere('o.customer in (:customers)')->setParameter('customers', $data['customers']);
        } else {
            $qb->andWhere('1=2'); // show none
        }

        $qb->orderBy('IFNULL(o.expectedInstallationDateTime, o.creationDateTime)', 'ASC');

        return $qb->getQuery();
    }

    public function fetchAllForUser(User $user, string $status, array $filters): array
    {
        $qb = $this->createQueryBuilder('o');
        $qb->select('o')
            ->join('o.rankings', 'r')
            ->andWhere('r.user = :user')->setParameter('user', $user)
            ->andWhere('r.displayDate IS NOT NULL')
            ->orderBy('IFNULL(o.expectedInstallationDateTime, o.creationDateTime)', 'ASC');

        // filters
        if (isset($filters['hardwood'])) $qb->andWhere('o.floorWoodMeters is not null and o.floorWoodMeters > 0');
        if (isset($filters['panels'])) $qb->andWhere('o.floorPanelMeters is not null and o.floorPanelMeters > 0');
        if (isset($filters['carpet'])) $qb->andWhere('o.floorCarpetMeters is not null and o.floorCarpetMeters > 0');
        if (isset($filters['door'])) $qb->andWhere('o.doorNumber is not null and o.doorNumber > 0');

        $dateConditions = [];
        if (isset($filters['7'])) $dateConditions[] = $qb->expr()->orX('o.expectedInstallationDateTime is not null and DateDiff(o.expectedInstallationDateTime, now()) < 7');
        if (isset($filters['8-14'])) $dateConditions[] = $qb->expr()->orX('o.expectedInstallationDateTime is not null and DateDiff(o.expectedInstallationDateTime, now()) between 8 and 14');
        if (isset($filters['14-28'])) $dateConditions[] = $qb->expr()->orX('o.expectedInstallationDateTime is not null and DateDiff(o.expectedInstallationDateTime, now()) between 14 and 28');
        if (isset($filters['28'])) $dateConditions[] = $qb->expr()->orX('o.expectedInstallationDateTime is not null and DateDiff(o.expectedInstallationDateTime, now()) > 28');
        if ($dateConditions) $qb->andWhere($qb->expr()->orX(...$dateConditions));

        switch ($status) {
            case AssemblyOrderService::STATUS_AVAILABLE:
                $qb->leftJoin('o.users', 'ou', 'WITH', 'ou.user = :user')
                    ->andWhere('o.taken = 0')
                    ->andWhere('ou.status is null or ou.status != :hidden')->setParameter('hidden', AssemblyOrderUser::STATUS_HIDDEN);
                break;
            case AssemblyOrderService::STATUS_ACCEPTED:
                $qb->join('o.users', 'ou', 'WITH', 'ou.user = :user and ou.status = :status')
                    ->setParameter('status', AssemblyOrderUser::STATUS_ACCEPTED);
                break;
            default:
                throw new \Exception('Invalid status');
        }

        return $qb->getQuery()->getResult();
    }

    public function fetchForUser(int $id, User $user): ?AssemblyOrder
    {
        $qb = $this->createQueryBuilder('o');
        $qb->select('o')
            ->join('o.rankings', 'r')
            ->andWhere('r.user = :user')->setParameter('user', $user)
            ->andWhere('o.id = :id')->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}