<?php

namespace EdpUser\Service;

use Doctrine\ORM\EntityManager,
    Zend\Authentication\AuthenticationService,
    SpiffyDoctrine\Authentication\Adapter\DoctrineEntity as DoctrineAuthAdapter,
    Zend\Authentication\Adapter as AuthAdapter;

class User
{
    /**
     * @var AuthAdapter
     */
    protected $authAdapter;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $entityClass = 'EdpUser\Entity\User';

    /**
     * @var Zend\Authentication\AuthenticationService
     */
    protected $authService;

    public function authenticate($username, $password)
    {
        $adapter     = $this->getAuthAdapter($username, $password);
        $authService = $this->getAuthService();
        $result      = $authService->authenticate($adapter);
        return $result;
    }

    /**
     * getEntityManager 
     * 
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * setEntityManager 
     * 
     * @param EntityManager $entityManager 
     * @return User
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * getAuthAdapter 
     * 
     * @param string $email 
     * @param string $password 
     * @return DoctrineAuthAdapter
     */
    public function getAuthAdapter($username, $password)
    {
        if (null === $this->authAdapter) {
            $authAdapter = new DoctrineAuthAdapter(
                $this->getEntityManager(),
                $this->entityClass
            );
            $this->setAuthAdapter($authAdapter);
        }
        $this->authAdapter->setIdentity($username)->setCredential($password);
        return $this->authAdapter;
    }

    /**
     * setAuthAdapter 
     * 
     * @param AuthAdapter $authAdapter 
     * @return User
     */
    public function setAuthAdapter(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
    }

    /**
     * getAuthService 
     * 
     * @return mixed
     */
    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = new AuthenticationService;
        }
        return $this->authService;
    }
    /**
     * setAuthenticationService 
     * 
     * @param mixed $authService 
     * @return User
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
        return $this;
    }
}
