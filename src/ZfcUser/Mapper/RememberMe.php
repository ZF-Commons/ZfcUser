<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class RememberMe extends AbstractDbMapper
{
    protected $tableName  = 'remember_me';

    public function findByEmail($email)
    {
        $select = $this->getSelect()
            ->from($this->tableName)
            ->where(array('email' => $email));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function findByEmailSerie($email, $serieId)
    {
        $select = $this->getSelect()
            ->from($this->tableName)
            ->where(array('email' => $email, 'sid' => $serieId));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function updateSerie($entity)
    {
        $where = 'email = ' . $entity->getEmail() . ' && sid = ' . $entity->getSid();
        $hydrator = new RememberMeHydrator;
        return parent::update($entity, $where, $this->tableName, $hydrator);
    }

    public function createSerie($entity)
    {
        $hydrator = new RememberMeHydrator;
        return parent::insert($entity, $this->tableName, $hydrator);
    }

    public function removeAll($email)
    {
        $where = 'email = ' . $email;
        return parent::delete($where, $this->tableName);
    }

    public function remove($entity)
    {
        $where = 'email = ' . $entity->getEmail() . ' && sid = ' . $entity->getSid() . ' && token = ' . $entity->getToken();
        return parent::delete($where, $this->tableName);
    }
}