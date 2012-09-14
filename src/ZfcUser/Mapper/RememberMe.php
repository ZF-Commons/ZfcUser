<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class RememberMe extends AbstractDbMapper
{
    protected $tableName  = 'remember_me';

    public function findById($userId)
    {
        $select = $this->getSelect()
            ->from($this->tableName)
            ->where(array('user_id' => $userId));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function findByIdSerie($userId, $serieId)
    {
        $select = $this->getSelect()
            ->from($this->tableName)
            ->where(array('user_id' => $userId, 'sid' => $serieId));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function updateSerie($entity)
    {
        $where = 'user_id = ' . $entity->getUserId() . ' && sid = "' . $entity->getSid() . '"';
        $hydrator = new RememberMeHydrator;
        return parent::update($entity, $where, $this->tableName, $hydrator);
    }

    public function createSerie($entity)
    {
        $hydrator = new RememberMeHydrator;
        return parent::insert($entity, $this->tableName, $hydrator);
    }

    public function removeAll($userId)
    {
        $where = 'user_id = ' . $userId;
        return parent::delete($where, $this->tableName);
    }

    public function remove($entity)
    {
        $where = 'user_id = ' . $entity->getUserId() . ' && sid = "' . $entity->getSid() . '" && token = "' . $entity->getToken() . '"';
        return parent::delete($where, $this->tableName);
    }

    public function removeSerie($userId, $serieId)
    {
        $where = 'user_id = ' . $userId . ' && sid = "' . $serieId . '"';
        return parent::delete($where, $this->tableName);
    }
}