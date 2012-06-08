<?php

namespace ZfcUser\Mapper;

use ArrayObject;
use DateTime;
use ZfcBase\Mapper\AbstractDbMapper;
use ZfcBase\Model\AbstractModel;
use ZfcUser\Module as ZfcUser;

class User extends AbstractDbMapper
{
    protected $tableName         = 'user';
    protected $primaryKey        = 'user_id';

    /**
     * Returns the class name of the object mapped by the data mapper
     *
     * @return string
     */
    public function getClassName()
    {
        return ZfcUser::getOption('user_model_class');
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    protected function fromRow($row)
    {
        if (!$row) return false;
        $userModelClass = $this->getClassName();
        $user = $userModelClass::fromArray($row->getArrayCopy());
        $user->setLastLogin(DateTime::createFromFormat('Y-m-d H:i:s', $row['last_login']));
        $user->setRegisterTime(DateTime::createFromFormat('Y-m-d H:i:s', $row['register_time']));
        return $user;
    }

    public function remove($model)
    {
        // TODO: Implement remove() method.
    }

    public function getPaginatorAdapter(array $params)
    {
        // TODO: Implement getPaginatorAdapter() method.
    }

}