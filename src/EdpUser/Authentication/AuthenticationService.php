<?php

namespace EdpUser\Authentication;

use EdpCommon\EventManager\EventProvider,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response,
    Zend\Authentication\Storage;

class AuthenticationService extends EventProvider
{
    /**
     * @var AuthEvent
     */
    protected $authEvent;

    /**
     * Persistent storage handler
     *
     * @var Storage
     */
    protected $storage;

    /**
     * Constructor
     * 
     * @param  Storage $storage 
     * @return void
     */
    public function __construct(Storage $storage = null)
    {
        if (null !== $storage) {
            $this->setStorage($storage);
        }
    }

    /**
     * Returns the persistent storage handler
     *
     * Session storage is used by default unless a different storage adapter has been set.
     *
     * @return Storage
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new Storage\Session());
        }

        return $this->storage;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  Storage $storage
     * @return AuthenticationService Provides a fluent interface
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * authenticate 
     * 
     * @param Request $request 
     * @return mixed (could be a response or an authentication result)
     */
    public function authenticate(Request $request)
    {
        $e = $this->getAuthEvent();
        $e->setRequest($request);

        $result = $this->events()->trigger('authenticate', $e, function($test) {
            return ($test instanceof Response);
        });

        if ($result->stopped()) {
            return $result->last();
        }

        if ($e->getIdentity()) {
            $this->getStorage()->write($e->getIdentity());
        } else {
            $this->clearIdentity();
        }
        // k, now what???

        // return AuthenticationResult
    }

    /**
     * Add authenticate adapter / listener 
     * 
     * @param mixed $adapter FQ class name or instance of Adapter
     * @param int $priority 
     * @return AuthenticationService
     */
    public function add($adapter, $priority = 0)
    {
        if (is_string($adapter) && class_exists($adapter)) {
            $adapter = new $adapter;
        }
        if (!$adapter instanceof Adapter) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid auth adapter provided. Expected instance of EdpUser\Athentication\Adapter, received %s.",
                get_class($adapter)
            ));
        }
        $this->events()->attach('authenticate', array($adapter, 'authenticate'), $priority);
        return $this;
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return !$this->getStorage()->isEmpty();
    }

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        $storage = $this->getStorage();

        if ($storage->isEmpty()) {
            return null;
        }

        return $storage->read();
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return void
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }

    
    /**
     * Get the auth event 
     * 
     * @return AuthEvent
     */
    protected function getAuthEvent()
    {
        if (null === $this->authEvent) {
            $this->authEvent = new AuthEvent;
        }
        return $this->authEvent;
    }
}
