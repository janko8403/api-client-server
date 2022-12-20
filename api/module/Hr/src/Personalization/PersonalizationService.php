<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.11.2016
 * Time: 11:08
 */

namespace Hr\Personalization;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Sql;

class PersonalizationService implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    const TYPE_MONITORING_SCORECARD = 'monitoringScorecard';
    const TYPE_MONITORING_DISABLE_FLOW = 'monitoringsDisableFlow';

    /**
     * @var array
     */
    private $cache;

    /**
     * Checks wheather requested personalization is active.
     *
     * @param string $name Personalization name
     * @return bool
     * @throws \Exception Thrown if requested personalization name doesn't exist
     */
    public function active(string $name)
    {
        if (empty($this->cache)) {
            $this->buildCache();
        }

        if (isset($this->cache[$name])) {
            return (bool)$this->cache[$name];

        } else {
            throw new \Exception(sprintf("Unknown personalization requested: `%s`", $name));
        }
    }

    /**
     * Builds internal personalization cache.
     */
    private function buildCache()
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select(['p' => '_memoryPersonalization']);
        $select->join(['d' => 'personalizationDetails'], 'p.id = d.personalizationId', ['isActive'], $select::JOIN_LEFT);
        $select->columns(['key']);

        $data = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);
        foreach ($data as $d) {
            $this->cache[$d->key] = $d->isActive;
        }
    }
}