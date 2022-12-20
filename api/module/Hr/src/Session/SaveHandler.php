<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.10.2016
 * Time: 17:49
 */

namespace Hr\Session;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Sql;
use Laminas\Session\SaveHandler\SaveHandlerInterface;

class SaveHandler implements SaveHandlerInterface
{
    /**
     * Session Save Path
     *
     * @var string
     */
    protected $sessionSavePath;

    /**
     * Session Name
     *
     * @var string
     */
    protected $sessionName = 'tikrow_client';

    /**
     * Lifetime
     *
     * @var int
     */
    protected $lifetime;

    /**
     * @var AdapterInterface
     */
    protected $dbAdapter;


    protected $tableName = 'session';

    /**
     * SaveHandler constructor.
     *
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * Open Session
     *
     * @param string $savePath
     * @param string $name
     * @return bool
     */
    public function open($savePath, $name)
    {
        $this->sessionSavePath = $savePath;
        $this->lifetime = ini_get('session.gc_maxlifetime');

        return true;
    }

    /**
     * Close session
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $id
     * @param bool   $destroyExpired Optional; true by default
     * @return string
     */
    public function read($id, $destroyExpired = true)
    {
        $db = $this->dbAdapter;

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tableName);
        $select->where(['id' => $id]);

        $resultSet = $db->query($sql->buildSqlString($select), $db::QUERY_MODE_EXECUTE);

        if ($resultSet->count()) {
            return $resultSet->current()->data;

            if ($destroyExpired) {
                $this->destroy($id);
            }
        }

        return '';
    }

    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return bool
     */
    public function write($id, $data)
    {
        $db = $this->dbAdapter;
        $sql = new Sql($this->dbAdapter);
        $read = $this->read($id);

        if (!empty($read)) {
            $command = $sql->update($this->tableName);
            $command->set([
                'data' => $data,
                'expires' => new Expression('DATE_ADD(NOW(), INTERVAL ' . $this->lifetime . ' SECOND)'),
            ]);

            $command->where(['id' => $id]);
        } else {
            $command = $sql->insert($this->tableName);
            $command->values([
                'data' => $data,
                'id' => $id,
                'expires' => new Expression('DATE_ADD(NOW(), INTERVAL ' . $this->lifetime . ' SECOND)'),
            ]);
        }

        return (bool)$db->query($sql->buildSqlString($command), $db::QUERY_MODE_EXECUTE);
    }

    /**
     * Destroy session
     *
     * @param string $id
     * @return bool
     */
    public function destroy($id)
    {
        die('d');
        $db = $this->dbAdapter;
        $sql = new Sql($this->dbAdapter);

        $delete = $sql->delete($this->tableName);
        $delete->where(['id' => $id]);

        return (bool)$db->query($sql->buildSqlString($delete), $db::QUERY_MODE_EXECUTE);
    }

    /**
     * Garbage Collection
     *
     * @param int $maxlifetime
     * @return true
     */
    public function gc($maxlifetime)
    {
        die('dgc');
        $db = $this->dbAdapter;
        $sql = new Sql($this->dbAdapter);

        $delete = $sql->delete($this->tableName);
        $delete->where('expires <= NOW()');

        return (bool)$db->query($sql->buildSqlString($delete), $db::QUERY_MODE_EXECUTE);
    }
}