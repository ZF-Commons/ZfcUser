<?php

namespace ZfcUser\Mapper;

use ArrayObject;
use DateTime;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use ZfcBase\Mapper\AbstractDbMapper;
use ZfcUser\Module as ZfcUser;

class User extends AbstractDbMapper
{
    /**
     * @var string
     */
    protected $tableName         = 'user';

    /**
     * @var string
     */
    protected $primaryKey        = 'user_id';

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * Returns the class name of the object mapped by the data mapper
     *
     * @return string
     */
    public function getClassName()
    {
        return ZfcUser::getOption('user_model_class');
    }

    /**
     * get table name
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * get primary key
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * create model from row
     *
     * @param \Zend\Db\ResultSet\Row|bool $row
     * @return object|false
     */
    public function fromRow($row)
    {
        if (!$row) return false;
        $data = $row->getArrayCopy();
        $className = $this->getClassName();
        if (isset($data[$this->getPrimaryKey()])) {
            $id = serialize($data[$this->getPrimaryKey()]);
            $user = $this->lookupIdentityMap($className,$id);
            if ($user) {
                return $user;
            }
        }
        $userModelClass = $this->getClassName();
        $user = new $userModelClass;
        $hydrator = $this->getHydrator();
        $user = $hydrator->hydrate($row->getArrayCopy(), $user);
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

    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if ($this->hydrator === NULL) {
            $this->hydrator = new ClassMethodsHydrator(false);
        }
        return $this->hydrator;
    }

}