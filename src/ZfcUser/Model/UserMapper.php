<?php

namespace ZfcUser\Model;

use ZfcBase\Mapper\DbMapperAbstract,
    ZfcUser\Module as ZfcUser,
    ArrayObject;

class UserMapper extends DbMapperAbstract implements UserMapperInterface
{
    protected $tableName         = 'user';
    protected $userIDField       = 'user_id';
    protected $userEmailField    = 'email';
    protected $userUsernameField = 'username';

    public function persist(User $user)
    {
        $data = new ArrayObject($user->toArray()); // or perhaps pass it by reference?
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'user' => $user));
        $db = $this->getWriteAdapter();
        if ($user->getUserId() > 0) {
            $db->update($this->getTableName(), (array) $data, $db->quoteInto($this->userIDField.' = ?', $user->getUserId()));
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
            ->where($this->userEmailField. ' = ?', $email);
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $userModelClass = ZfcUser::getOption('user_model_class');
        $user = $userModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    public function findByUsername($username)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where($this->userUsernameField.' = ?', $username);
        $this->events()->trigger(__FUNCTION__, $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $userModelClass = ZfcUser::getOption('user_model_class');
        return $userModelClass::fromArray($row);
    }

    public function findById($id)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where($this->userIDField.' = ?', $id);
        $this->events()->trigger(__FUNCTION__, $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $userModelClass = ZfcUser::getOption('user_model_class');
        return $userModelClass::fromArray($row);
    }
}
