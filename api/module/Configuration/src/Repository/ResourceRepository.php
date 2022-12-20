<?php

namespace Configuration\Repository;


use Configuration\Entity\Resource;
use Configuration\Entity\ResourcePosition;
use Hr\Repository\BaseRepository;
use Hr\Repository\SearchableInterface;
use Doctrine\ORM\Query;

/**
 * ResourceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResourceRepository extends BaseRepository implements SearchableInterface
{
    public function search(array $data): Query
    {
        $qb = $this->createQueryBuilder('m');

        empty($data['name']) ?: $qb->andWhere('m.name LIKE :name')
            ->setParameter('name', "%$data[name]%");

        empty($data['route']) ?: $qb->andWhere('m.route LIKE :route')
            ->setParameter('route', "%{$data['route']}%");

        empty($data['label']) ?: $qb->andWhere('m.label LIKE :label')
            ->setParameter('label', "%{$data['label']}%");
//        $order = $data['order'] ?? 'asc';

        $qb->andWhere($qb->expr()->isNull('m.parent'));

        $qb->orderBy('m.sequence', 'asc');
//        if (!empty($data['sort'])) {
//            $qb->orderBy($data['sort'], $order);
//        }

        return $this->addOrder($qb, $data);
    }

    public function findChildByRoute(string $route)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere('r.route = :route')->setParameter('route', $route);
        $qb->andWhere($qb->expr()->isNotNull('r.parent'));
        $data = $qb->getQuery()->execute();

        if (empty($data)) {
            $parts = explode('/', $route, 2);
            $qb->resetDQLPart('where');
            $qb->andWhere('r.route = :route')->setParameter('route', $parts[0]);

            $data = $qb->getQuery()->execute();
            if (empty($data)) {
                throw new \Exception("Cannot find resource for route `$route`");
            }
        }

        return $data[0];
    }

    public function getParentUserMenuResources()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere($qb->expr()->isNull('r.parent'));
        $qb->andWhere($qb->expr()->eq('r.isUserMenu', true));
        $qb->andWhere($qb->expr()->eq('r.isHidden', 0));
        $qb->orderBy('r.sequence');

        return $qb->getQuery()->execute();
    }

    public function getShortcuts()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('r');
        $qb->leftJoin('r.parent', 'p');
        $qb->andWhere($qb->expr()->eq('r.isShortcutMenu', 1));
        $qb->andWhere($qb->expr()->eq('r.isHidden', 0));
        $qb->orderBy('p.sequence');
        $qb->orderBy('r.sequence');

        return $qb->getQuery()->execute();
    }

    public function getCustomerMonitoringGroups()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere('r.isCustomerMenu = 1');
        $qb->andWhere('r.isHidden = 0');
        $qb->andWhere($qb->expr()->isNotNull('r.monitoringCategory'));
        $qb->orderBy('r.sequence', 'asc');

        return $qb->getQuery()->execute();
    }

    public function getCustomerMenu()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere('r.isCustomerMenu = 1');
        $qb->andWhere('r.isHidden = 0');
        $qb->andWhere($qb->expr()->isNull('r.monitoringCategory'));
        $qb->orderBy('r.sequence', 'asc');

        return $qb->getQuery()->execute();
    }

    public function getUserDetailsMenu()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere('r.isUserDetailsMenu = 1');
        $qb->andWhere('r.isHidden = 0');
        $qb->andWhere($qb->expr()->isNull('r.monitoringCategory'));
        $qb->orderBy('r.sequence', 'asc');

        return $qb->getQuery()->execute();
    }

    public function getUserMonitoringGroups()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->andWhere('r.isUserDetailsMenu = 1');
        $qb->andWhere('r.isHidden = 0');
        $qb->andWhere($qb->expr()->isNotNull('r.monitoringCategory'));
        $qb->orderBy('r.sequence', 'asc');

        return $qb->getQuery()->execute();
    }

    public function getResourcesForCustomerApplication(int $positionId): array
    {
        $qb = $this->createQueryBuilder('r');
        $qb->innerJoin('r.resourcePositions', 'rp');
        $qb->andWhere('r.name like :name')->setParameter('name', '%customer:application:%');
        $qb->andWhere('rp.position = :position')->setParameter('position', $positionId);
        $qb->andWhere('rp.settingSmall = :yes or rp.settingMedium = :yes or rp.settingLarge = :yes')
            ->setParameter('yes', ResourcePosition::SETTING_YES);
        $qb->andWhere('r.settingSmall = :yesr or r.settingMedium = :yesr or r.settingLarge = :yesr')
            ->setParameter('yesr', Resource::SETTING_YES);

        return $qb->getQuery()->execute();
    }
}