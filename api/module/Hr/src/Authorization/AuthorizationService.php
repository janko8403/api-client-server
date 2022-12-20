<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.10.2016
 * Time: 16:25
 */

namespace Hr\Authorization;


use Laminas\Authentication\AuthenticationService as LaminasAuthenticationService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Sql;

class AuthorizationService implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    /**
     * Level 0 - no access
     */
    const LVL_NONE = 0;

    /**
     * Level 1 - only for me
     */
    const LVL_MINE = 1;

    /**
     * Level 2 - me and direct subordinates
     */
    const LVL_SUBS = 2;

    /**
     * Level 9 - me, my subordinates and their subordinates
     */
    const LVL_ALLSUBS = 9;

    /**
     * Level 10 - all data
     */
    const LVL_ALL = 10;

    /**
     * View mode
     */
    const MODE_VIEW = 'view';

    /**
     * Add mode
     */
    const MODE_ADD = 'add';

    /**
     * Delete mode
     */
    const MODE_DELETE = 'delete';

    /**
     * Edit/save mode
     */
    const MODE_SAVE = 'save';

    /**
     * @var LaminasAuthenticationService
     */
    private $authenticationService;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * AuthorizationService constructor.
     *
     * @param LaminasAuthenticationService $service
     * @param AbstractAdapter              $cacheAdapter
     */
    public function __construct(LaminasAuthenticationService $service, AbstractAdapter $cacheAdapter)
    {
        $this->authenticationService = $service;
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * Gets access level for given resource and operation.
     *
     * @param string $resourceName
     * @param string $operation
     * @return int|mixed
     */
    public function getAccessLevel(string $resourceName, string $operation)
    {
        $identity = $this->authenticationService->getIdentity();

        $cacheId = sprintf('auth_%s_g%s', $resourceName, implode('-', $identity['groupIds']));
        $result = $this->cacheAdapter->getItem($cacheId, $success);
        if (!$success) {
            $result = $this->getPermissions($resourceName, $identity['groupIds']);
            $this->cacheAdapter->setItem($cacheId, $result);
        }

        return $result[$operation] ?? 0;
    }

    /**
     * Checks wheather current user is allowed to make given operation on given resource.
     *
     * @param string $resourceName
     * @param string $operation
     * @return bool
     */
    public function isAllowed(string $resourceName, string $operation): bool
    {
        $level = $this->getAccessLevel($resourceName, $operation);

        return $level > self::LVL_NONE;
    }

    /**
     * Checks wheather given operation has level at least level LVL_MINE for given resource.
     *
     * @param string $resourceName
     * @param string $operation
     * @return bool
     */
    public function isLevelMine(string $resourceName, string $operation): bool
    {
        $level = $this->getAccessLevel($resourceName, $operation);

        return $level >= self::LVL_MINE;
    }

    /**
     * Checks wheather given operation has level at least level LVL_SUBS for given resource.
     *
     * @param string $resourceName
     * @param string $operation
     * @return bool
     */
    public function isLevelSubs(string $resourceName, string $operation): bool
    {
        $level = $this->getAccessLevel($resourceName, $operation);

        return $level >= self::LVL_SUBS;
    }

    /**
     * Checks wheather given operation has level at least level LVL_ALLSUBS for given resource.
     *
     * @param string $resourceName
     * @param string $operation
     * @return bool
     */
    public function isLevelAllSubs(string $resourceName, string $operation): bool
    {
        $level = $this->getAccessLevel($resourceName, $operation);

        return $level >= self::LVL_ALLSUBS;
    }

    /**
     * Checks wheather given operation has level at least level LVL_ALL for given resource.
     *
     * @param string $resourceName
     * @param string $operation
     * @return bool
     */
    public function isLevelAll(string $resourceName, string $operation): bool
    {
        $level = $this->getAccessLevel($resourceName, $operation);

        return $level >= self::LVL_ALL;
    }

    /**
     * Gets permissions for giver resource and user group ids.
     *
     * @param string $resourceName
     * @param array  $groupIds
     * @return array
     */
    private function getPermissions(string $resourceName, array $groupIds)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(['r' => '_memoryResources']);
        $select->columns([]);
        $select->join(['p' => '_memoryPermissions'], 'r.id = p.resourceId', ['add', 'save', 'delete', 'view']);
        $select->where('p.isActive = 1');
        $select->where(['r.key' => $resourceName]);
        $select->where(['p.groupId' => $groupIds]);
        $data = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE)->toArray();

        $tmp = [];
        array_walk($data, function ($item) use (&$tmp) {
            foreach ($item as $k => $v) {
                if (!isset($tmp[$k]) || $tmp[$k] < $v)
                    $tmp[$k] = $v;

            }
        });

        return $tmp;
    }
}