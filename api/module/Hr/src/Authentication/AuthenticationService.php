<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.10.2016
 * Time: 16:25
 */

namespace Hr\Authentication;


use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Authentication\AuthenticationService as LaminasAuthenticationService;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Sql;
use Laminas\Http\Response;
use Laminas\Stdlib\ResponseInterface;

class AuthenticationService implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    /**
     * AuthenticationService constructor.
     *
     * @param LaminasAuthenticationService $service
     * @param Response                     $response
     */
    public function __construct(private LaminasAuthenticationService $service, private ResponseInterface $response)
    {
    }

    /**
     * Authenticates user with login and password
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function authenticateWithPassword(string $login, string $password): bool
    {
        if (empty($login) || empty($password)) {
            return false;
        }

        return $this->processUserData(['login' => $login, 'password' => $password], true);
    }

    /**
     * Processes provided user data. Logs user in.
     *
     * @param array $userData
     * @param bool  $sha1Password
     * @return bool
     */
    private function processUserData(array $userData, bool $sha1Password = false): bool
    {
        $sql = new Sql($this->adapter);

        $adapter = new CallbackCheckAdapter(
            $this->adapter,
            'user',
            'login',
            'password',
            function ($hash, $password) {
                return (new Bcrypt())->verify($password, $hash);
            }
        );
        $adapter->setIdentity($userData['login']);
        $adapter->setCredential($userData['password']);
        $adapter->setAmbiguityIdentity(true);

        $result = $this->service->authenticate($adapter);
        if ($result->isValid()) {
            $userData = $adapter->getResultRowObject(null, ['password']);
            // get groups for user
            $select = $sql->select('userGroups');
            $select->columns(['groupId']);
            $select->where(['userId' => $userData->id]);
            $groupsData = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);

            $userData->groupIds = [];
            foreach ($groupsData as $gd) {
                $userData->groupIds[] = $gd['groupId'];
            }

            if (!empty($userData->groupIds)) {
                $select = $sql->select('_memoryGroups');
                $select->columns(['name']);
                $select->where(['id' => $userData->groupIds]);
                $select->order('id');
                $groupNames = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);

                $userData->groupNames = [];
                foreach ($groupNames as $gn) {
                    $userData->groupNames[] = $gn['name'];
                }
            }

            $this->service->getStorage()->write((array)$userData);

            return true;
        }

        return false;
    }

    /**
     * Checks wheather user has identity.
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->service->hasIdentity();
    }

    /**
     * Gets user identity.
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->service->getIdentity();
    }

    /**
     * Clears user identity.
     */
    public function clearIdentity()
    {
        return $this->service->clearIdentity();
    }

    public function updateSessionRecord(string $sessionS3, string $sessionS2)
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('session');
        $select->where(['id' => $sessionS3, 'name' => 'PHPSESSID']);
        $data = $this->adapter->query($sql->buildSqlString($select), Adapter::QUERY_MODE_EXECUTE);

        if ($data->count()) {
            // update session
            $recordId = $data->current()->recordId;
            $query = $sql->update('session');
            $query->set(['session_id_s2' => $sessionS2]);
            $query->where(['recordId' => $recordId]);
        } else {
            // insert session
            $query = $sql->insert('session');
            $query->values([
                'id' => $sessionS3,
                'name' => 'PHPSESSID',
                'session_id_s2' => $sessionS2,
                'expires' => date('Y-m-d H:i:s', time() + 1800),
                'modified' => time(),
                'lifetime' => 3600,
            ]);
        }

        $this->adapter->query($sql->buildSqlString($query), Adapter::QUERY_MODE_EXECUTE);
    }
}