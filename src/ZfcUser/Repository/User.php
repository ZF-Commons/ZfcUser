<?php

namespace ZfcUser\Repository;

use ArrayObject;
use DateTime;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use ZfcBase\Repository\AbstractDbRepository;
use ZfcUser\Mapper\User as UserMapper;
use ZfcUser\Module as ZfcUser;

class User extends AbstractDbRepository implements UserInterface
{
    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var string
     */
    protected $userEmailField    = 'email';

    /**
     * @var string
     */
    protected $userUsernameField = 'username';

    /**
     * Constructor
     *
     * @param User $mapper
     */
    public function __construct(UserMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function findByEmail($email)
    {
        $rowset = $this->getMapper()->getTableGateway()->select(array($this->userEmailField => $email));
        $row = $rowset->current();
        $user = $this->fromRow($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user));
        return $user;
    }

    public function findByUsername($username)
    {
        $rowset = $this->getMapper()->getTableGateway()->select(array($this->userUsernameField => $username));
        $row = $rowset->current();
        $user = $this->fromRow($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user));
        return $user;
    }

    public function find($id)
    {
        $user = $this->getMapper()->find($id);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user));
        return $user;
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

    /**
     * Finds all objects in the repository.
     *
     * @return mixed The objects.
     */
    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria
     * @return object The object.
     */
    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * Returns the class name of the object managed by the repository
     *
     * @return string
     */
    public function getClassName()
    {
        $mapper = $this->getMapper();
        return $mapper->getClassName();
    }

}
