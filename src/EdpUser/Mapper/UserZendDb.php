<?php

namespace EdpUser\Mapper;

use EdpCommon\Mapper\DbMapperAbstract,
    EdpUser\Model\User as UserModel,
    Zend\Authentication\Adapter\DbTable as DbAdapter;

class UserZendDb extends DbMapperAbstract implements UserInterface
{
    protected $tableName = 'user';
    protected $authAdapter;
    protected $emailValidator;

    public function persist(UserModel $user)
    {
        $data = array(
            'user_id'       => $user->getUserId(),
            'email'         => $user->getEmail(),
            'display_name'  => $user->getDisplayName(),
            'password'      => $user->getPassword(),
            'salt'          => $user->getSalt(),
            'register_time' => $user->getRegisterTime()->format('Y-m-d H:i:s'),
            'register_ip'   => $user->getRegisterIp(true),
            'last_login'    => $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : null,
            'last_ip'       => $user->getLastIp() ? $user->getLastIp(true) : null,
        );
        $db = $this->getWriteAdapter();
        if ($user->getUserId() > 0) {
            $db->update($this->getTableName(), $data, $db->quoteInto('user_id = ?', $user->getUserId()));
        } else {
            $db->insert($this->getTableName(), $data);
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
        $row = $db->fetchRow($sql);
        return UserModel::fromArray($row);
    }

    public function findByUsername($username)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('username = ?', $username);
        $row = $db->fetchRow($sql);
        return UserModel::fromArray($row);
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
