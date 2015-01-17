<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Stdlib\PriorityList;
use Zend\Authentication\Result;

class AdapterChain extends AbstractAdapter
{
    /**
     * @var PriorityList
     */
    protected $adapters;
    
    public function __construct() 
    {
        $this->adapters = new PriorityList();
    }

    public function attach($name, AbstractAdapter $adapter, $priority = 1)
    {
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
        foreach ( $this->adapters as $adapter ) {
            $adapter->setIdentity($this->getIdentity());
            $adapter->setCredential($this->getCredential());
            
            $result = $adapter->authenticate();
            if ( $result->isValid() ) {
                return $result;
            }
        }
        
        //@TODO throw an exception if no result? (no adapters tried)
        
        return $result instanceof Result
            ? $result
            : new Result(Result::FAILURE_UNCATEGORIZED, null);
    }
}
