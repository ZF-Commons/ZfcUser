<?php

namespace ZfcUser\Model;

use ZfcBase\Mapper\DbMapperAbstract,
    ZfcUser\Module as ZfcUser,
    ArrayObject,
    DateTime;

class UserMapper extends DbMapperAbstract implements UserMapperInterface
{
    protected $tableName         = 'user';
    protected $userIDField       = 'user_id';
    protected $userEmailField    = 'email';
    protected $userUsernameField = 'username';

    public function persist(UserInterface $user)
    {
        $data = new ArrayObject($this->toScalarValueArray($user)); // or perhaps pass it by reference?
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'user' => $user));
        if ($user->getUserId() > 0) {
            $this->getTableGateway()->update((array) $data, array($this->userIDField => $user->getUserId()));
        } else {
            $this->getTableGateway()->insert((array) $data);
            $userId = $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->getLastGeneratedId();
            $user->setUserId($userId);
        }
        return $user;
    }

    public function findByEmail($email)
    {
        $rowset = $this->getTableGateway()->select(array($this->userEmailField => $email));
        $row = $rowset->current();
        $user = $this->fromRow($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    public function findByUsername($username)
    {
        $rowset = $this->getTableGateway()->select(array($this->userUsernameField => $username));
        $row = $rowset->current();
        $user = $this->fromRow($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    public function findById($id)
    {
        $rowset = $this->getTableGateway()->select(array($this->userIDField => $id));
        $row = $rowset->current();
        $user = $this->fromRow($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    protected function fromRow($row)
    {
        if (!$row) return false;
        $userModelClass = ZfcUser::getOption('user_model_class');
        $user = $userModelClass::fromArray($row->getArrayCopy());
        $user->setLastLogin(DateTime::createFromFormat('Y-m-d H:i:s', $row['last_login']));
        $user->setRegisterTime(DateTime::createFromFormat('Y-m-d H:i:s', $row['register_time']));
        return $user;
    }
}
