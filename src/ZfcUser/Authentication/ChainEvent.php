<?php

namespace ZfcUser\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\EventManager\Event;

class ChainEvent extends Event
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var null
     */
    protected $identity = null;

    /**
     * @var int
     */
    protected $code = Result::FAILURE;

    /**
     * @var array
     */
    protected $messages = array();

    /**
     * @param AdapterChain $adapter
     */
    public function __construct(AdapterChain $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param int $code
     * @return ChainEvent
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param null $identity
     * @return ChainEvent
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return null
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return ChainEvent
     */
    public function clearMessages()
    {
        $this->messages = array();
        return $this;
    }

    /**
     * @param array $messages
     * @return ChainEvent
     */
    public function addMessages(array $messages)
    {
        $this->setMessages(array_merge($messages, $this->messages));
        return $this;
    }

    /**
     * @param array $messages
     * @return ChainEvent
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}