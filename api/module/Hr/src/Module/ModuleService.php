<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.12.2016
 * Time: 20:06
 */

namespace Hr\Module;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Sql;

class ModuleService
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * ModuleService constructor.
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Checks wheather module is active.
     *
     * @param string $key
     * @return bool
     */
    public function isActive(string $key): bool
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select(['m' => '_memoryModules']);
        $select->columns(['no' => new Expression('COUNT(*)')]);
        $select->join(['d' => 'modulesDetails'], 'm.id = d.moduleId', []);
        $select->where([
            'd.isActive' => 1,
            'm.key' => $key,
        ]);

        $data = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);

        return $data->current()->no > 0;
    }

    public function mainMenu(): array
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select(['m' => '_memoryModules']);
        $select->columns(['key']);
        $select->join(['mD' => 'modulesDetails'], 'm.id = mD.moduleId', ['name']);
        $select->where([
            'isActive' => 1,
            'isMonitoring = 0 OR isMainMenu = 1',
        ]);
        $select->group(['key']);
        $select->order(['mD.order']);
        $data = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);
        $lines = $data->toArray();

        foreach ($lines as $line) {
            $modules[$line['key']] = $line['name'];
        }

        return $modules;
        // return $lines;
    }
}