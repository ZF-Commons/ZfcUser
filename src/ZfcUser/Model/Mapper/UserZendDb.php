<?php

namespace ZfcUser\Model\Mapper;

use ZfcBase\Mapper\DbMapperAbstract,
    ZfcUser\Module as ZfcUser,
    ZfcUser\Model\User as UserModel,
    ZfcUser\Model\Mapper\User as UserMapper,
    ArrayObject;

class UserZendDb extends DbMapperAbstract implements UserMapper
{
    protected $tableName         = 'user';
    protected $userIDField       = 'user_id';
    protected $userEmailField    = 'email';
    protected $userUsernameField = 'username';

    public function persist(UserModel $user)
    {
        $data = new ArrayObject($user->toArray()); // or perhaps pass it by reference?
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
        $userModelClass = ZfcUser::getOption('user_model_class');
        $user = $userModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    public function findByUsername($username)
    {
        $rowset = $this->getTableGateway()->select(array($this->userUsernameField => $username));
        $row = $rowset->current();
        $userModelClass = ZfcUser::getOption('user_model_class');
        $user = $userModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }

    public function findById($id)
    {
        $rowset = $this->getTableGateway()->select(array($this->userIDField => $id));
        $row = $rowset->current();
        $userModelClass = ZfcUser::getOption('user_model_class');
        $user = $userModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'row' => $row));
        return $user;
    }
}
