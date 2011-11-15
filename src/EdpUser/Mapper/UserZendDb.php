<?php

namespace EdpUser\Mapper;

use EdpCommon\Mapper\DbMapperAbstract,
    EdpUser\Module,
    EdpUser\ModelBase\UserBase,
    Zend\Authentication\Adapter\DbTable as DbAdapter,
    ArrayObject;

class UserZendDb extends DbMapperAbstract implements UserInterface
{
    protected $tableName = 'user';
    protected $authAdapter;
    protected $emailValidator;

    public function persist(UserBase $user)
    {
        $data = new ArrayObject(array(
            'user_id'        => $user->getUserId(),
            'email'          => $user->getEmail(),
            'display_name'   => $user->getDisplayName(),
            'password'       => $user->getPassword(),
            'salt'           => $user->getSalt(),
            'hash_algorithm' => $user->getHashAlgorithm(),
            'last_login'     => $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : null,
            'last_ip'        => $user->getLastIp(true),
            'register_time'  => $user->getRegisterTime()->format('Y-m-d H:i:s'),
            'register_ip'    => $user->getRegisterIp(true),
            'active'         => $user->getActive(),
            'enabled'        => $user->getEnabled(),
        ));
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'user' => $user));
        $db = $this->getWriteAdapter();
        if ($user->getUserId() > 0) {
            $db->update($this->getTableName(), (array) $data, $db->quoteInto('user_id = ?', $user->getUserId()));
        } else {
            $db->insert($this->getTableName(), (array) $data);
            $userId = $db->lastInsertId();
            $user->setUserId($userId);
        }
        return $user;
    }

    public function findByEmail($email)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('email = ?', $email);
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $userModelClass = Module::getOption('user_model_class');
        $user = $userModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    public function findByUsername($username)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('username = ?', $username);
        $this->events()->trigger(__FUNCTION__, $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $UserModelClass = Model::getOption('user_model_class');
        return $userModelClass::fromArray($row);
    }

    public function getAuthAdapter($identity, $credential, $identityColumn)
    {
        if (null === $this->authAdapter) {
            $this->authAdapter = new DbAdapter(
                $this->getReadAdapter(),
                $this->getTableName(),
                $identityColumn,
                'password'
            );
        }
        $this->authAdapter->setIdentity($identity)
                          ->setCredential($credential)
                          ->setIdentityColumn($identityColumn);
        return $this->authAdapter;
    }

    public function getEmailValidator()
    {
        if (null === $this->emailValidator) {
            $this->emailValidator = array('Db\NoRecordExists', true, array(
                'adapter'   => $this->getReadAdapter(),
                'table'     => $this->getTableName(),
                'field'     => 'email'
            ));
        }
        return $this->emailValidator;
    }
}
