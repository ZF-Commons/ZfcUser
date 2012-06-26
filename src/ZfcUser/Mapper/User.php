<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

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
}
