<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use ZfcUser\Entity\UserInterface as UserEntityInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

class User extends AbstractDbMapper implements UserInterface
{
    protected $tableName  = 'user';

    public function findByEmail($email)
    {
        $select = $this->select()
                       ->from($this->tableName)
                       ->where(array('email' => $email));

        $entity = $this->selectWith($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function findByUsername($username)
    {
        $select = $this->select()
                       ->from($this->tableName)
                       ->where(array('username' => $username));

        $entity = $this->selectWith($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function findById($id)
    {
        $select = $this->select()
                       ->from($this->tableName)
                       ->where(array('user_id' => $id));

        $entity = $this->selectWith($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        if (!$where) {
            $where = 'user_id = ' . $entity->getId();
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }
}
