<?php

namespace EdpUser\Authentication;

use Zend\EventManager\Event,
    Zend\Stdlib\RequestDescription as Request;

class AuthEvent extends Event
{
    /**
     * getIdentity 
     * 
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->getParam('identity');
    }

    /**
     * setIdentity 
     * 
     * @param mixed $identity 
     * @return AuthEvent
     */
    public function setIdentity($identity = null)
    {
        $this->setParam('identity', $identity);
        return $this;
    }

    /**
     * getRequest 
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->getParam('request');
    }

    /**
     * setRequest 
     * 
     * @param Request $request 
     * @return AuthEvent
     */
    public function setRequest(Request $request)
    {
        $this->setParam('request', $request);
        $this->request = $request;
        return $this;
    }
}
