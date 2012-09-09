<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Options\RememberMeServiceOptionsInterface;
use Zend\Math\Rand;

class RememberMe extends EventProvider implements ServiceManagerAwareInterface
{
    protected $mapper;

    protected $serviceManager;

    public function createToken($length = 16)
    {
        $rand = new Rand;
        return $rand->getBytes($length, true);
    }

    public function createSerieId($length = 16)
    {
        $rand = new Rand;
        return $rand->getBytes($length, true);
    }

    public function updateSerie($email, $sid)
    {
        $rememberMe = $this->getMapper()->findByEmailSerie($email, $sid);
        if($rememberMe){
            // Remove old token
            $this->getMapper()->remove($rememberMe);

            // Create new token with same serie id
            $token = $this->createToken();
            $rememberMe->setToken($token);
            $this->setCookie($rememberMe);
            $this->getMapper()->updateSerie($rememberMe);
            return $token;
        }
        return false;
    }

    public function createSerie($email)
    {
        $token = $this->createToken();
        $serieId = $this->createSerieId();

        $rememberMe = new ZfcUser\Entity\RememberMe;
        $rememberMe->setEmail($email);
        $rememberMe->setSid($serieId);
        $rememberMe->setToken($token);

        if($this->setCookie($rememberMe))
        {
            $rememberMe = $this->getMapper()->createSerie($rememberMe);
            return $rememberMe;
        }

        return false;
    }

    /**
     * @TODO: Set expire
     * @param $entity
     * @return bool
     */
    public function setCookie($entity)
    {
        $cookieValue = $entity->getEmail() . "\n" . $entity->getSid() . "\n" . $entity->getToken();
        return setcookie("remember_me", $cookieValue);
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    public function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceManager()->get('zfcuser_rememberme_mapper');
        }
        return $this->mapper;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $locator
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}