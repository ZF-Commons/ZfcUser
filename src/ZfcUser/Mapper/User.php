<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use ZfcUser\Entity\UserInterface as UserEntityInterface;
use ZfcUser\Exception;
use Zend\Stdlib\Hydrator\HydratorInterface;

class User extends AbstractDbMapper implements UserInterface
{
    protected $tableName  = 'user';

    protected $identityFields;

    /**
     * @param  array $identityField
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(array $identityFields)
    {
        $this->identityFields = $identityFields;
    }

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

    public function findByIdentity($identity)
    {
        $userObject = null;

        // Cycle through the configured identity sources and test each
        while ( !is_object($userObject) && count($this->identityFields) > 0 ) {
            $mode = array_shift($this->identityFields);
            switch ($mode) {
                case 'username':
                    $userObject = $this->findByUsername($identity);
                    break;
                case 'email':
                    $userObject = $this->findByEmail($identity);
                    break;
            }
        }

        return $userObject;
    }

    public function findById($id)
    {
        $select = $this->getSelect()
                       ->where(array('user_id' => $id));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function getTableName(){
        return $this->tableName;
    }
    
    public function setTableName($tableName){
        $this->tableName=$tableName;
    }    
    
    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $result = parent::insert($entity, $tableName, $hydrator);
        $entity->setId($result->getGeneratedValue());
        return $result;
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        if (!$where) {
            $where = 'user_id = ' . $entity->getId();
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }
}
