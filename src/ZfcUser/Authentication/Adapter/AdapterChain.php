<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Stdlib\PriorityList;
use Zend\Authentication\Result;
use ZfcBase\EventManager\EventProvider;

class AdapterChain extends EventProvider implements ValidatableAdapterInterface
{
    /**
     * @var PriorityList
     */
    protected $adapters;
    
    /**
     * @var mixed
     */
    protected $credential;

    /**
     * @var mixed
     */
    protected $identity;
    
    public function __construct()
    {
        $this->adapters = new PriorityList();
        $this->adapters->isLIFO(false);
    }

    public function attach($name, AbstractAdapter $adapter, $priority = 1)
    {
        $argv = compact('name', 'adapter', 'priority');
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        
        $this->adapters->insert($name, $adapter, $priority);
        return $this;
    }
    
    /**
     * Returns the authentication result
     *
     * @return AuthenticationResult
     */
    public function authenticate()
    {
        $response = $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this);
        if ($response->stopped()) {
            return $response->last();
        }
        
        foreach ($this->adapters as $adapter) {
            $adapter->setIdentity($this->getIdentity());
            $adapter->setCredential($this->getCredential());
            
            $result = $adapter->authenticate();
            if ($result->isValid()) {
                $argv = compact('adapter', 'result');
                $this->getEventManager()->trigger(__FUNCTION__ . '.success', $this, $argv);
                return $result;
            }
        }
        
        //@TODO throw an exception if no result? (no adapters tried)
        
        if (!isset($result) || ! $result instanceof Result) {
            $result = new Result(Result::FAILURE_UNCATEGORIZED, null);
        }
        
        $argv = compact('result');
        $this->getEventManager()->trigger(__FUNCTION__ . '.failure', $this, $argv);
        
        return $result;
    }
    

    /**
     * Returns the credential of the account being authenticated, or
     * NULL if none is set.
     *
     * @return mixed
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * Sets the credential for binding
     *
     * @param  mixed           $credential
     * @return AbstractAdapter
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * Returns the identity of the account being authenticated, or
     * NULL if none is set.
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Sets the identity for binding
     *
     * @param  mixed          $identity
     * @return AbstractAdapter
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }
}
