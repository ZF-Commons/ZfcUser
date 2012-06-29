<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;

class User extends AbstractDbMapper implements UserInterface
{
    protected $tableName  = 'user';

    public function findByEmail($email)
    {
        $select = $this->select()
                       ->from($this->tableName)
                       ->where(array('email' => $email));

        return $this->selectWith($select)->current();
    }

    public function findByUsername($username)
    {
        $select = $this->select()
                       ->from($this->tableName)
                       ->where(array('username' => $username));

        return $this->selectWith($select)->current();
    }

    public function findById($id)
    {
        $select = $this->select()
                       ->from($this->tableName)
                       ->where(array('user_id' => $id));

        return $this->selectWith($select)->current();
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        if (!$where) {
            $where = 'user_id = ' . $entity->getId();
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }
}
