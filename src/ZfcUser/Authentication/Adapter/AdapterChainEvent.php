<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\EventManager\Event;
use Zend\Stdlib\RequestInterface as Request;

class AdapterChainEvent extends Event
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
     * @return AdapterChainEvent
     */
    public function setIdentity($identity = null)
    {
        if (null === $identity) {
            // Setting the identity to null resets the code and messages.
            $this->setCode();
            $this->setMessages();
        }
        $this->setParam('identity', $identity);
        return $this;
    }
    
    public function getCredential()
    {
        return $this->getParam('credential');
    }
    
    public function setCredential($credential)
    {
        $this->setParam('credential', $credential);
        return $this;
    }

    /**
     * getCode
     *
     * @return int
     */
    public function getCode()
    {
        return $this->getParam('code');
    }

    /**
     * setCode
     *
     * @param int $code
     * @return AdapterChainEvent
     */
    public function setCode($code = null)
    {
        $this->setParam('code', $code);
        return $this;
    }

    /**
     * getMessages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->getParam('messages') ?: array();
    }

    /**
     * setMessages
     *
     * @param array $messages
     * @return AdapterChainEvent
     */
    public function setMessages($messages = array())
    {
        $this->setParam('messages', $messages);
        return $this;
    }
}
