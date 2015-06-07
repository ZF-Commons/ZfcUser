<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Stdlib\Hydrator\HydratorInterface as Hydrator;

class User extends AbstractDbMapper implements UserInterface
{
    protected $tableName  = 'user';

    public function findByEmail($email)
    {
        $select = $this->getSelect()
                       ->where(array('email' => $email));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function findByUsername($username)
    {
        $select = $this->getSelect()
                       ->where(array('username' => $username));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function findById($id)
    {
        $select = $this->getSelect()
                       ->where(array('id' => $id));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function insert($entity, $tableName = null, Hydrator $hydrator = null)
    {
        $hydrator = $hydrator ?: $this->getHydrator();
        $result = parent::insert($entity, $tableName, $hydrator);
        return $result;
    }

    public function update($entity, $where = null, $tableName = null, Hydrator $hydrator = null)
    {
        if (!$where) {
            $where = array('id' => $entity->getId());
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }
}
