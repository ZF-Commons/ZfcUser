<?php

namespace EdpUser\Authentication;

use EdpCommon\EventManager\EventProvider,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response,
    Zend\Authentication\Storage,
    Zend\EventManager\ListenerAggregate;

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
     * @var mixed
     */
    protected $resolvedIdentity;

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

        if ($e->getIdentity()) {
            $this->getStorage()->write($e->getIdentity());
        } else {
            $this->clearIdentity();
        }

        if ($result->stopped() && $result->last() instanceof Response) {
            return $result->last();
        }

        // k, now what???

        // return AuthenticationResult
    }

    // TODO: Figure out when the adapter's internal storage should be cleared?
    public function clearAdapterStorage()
    {
        foreach ($this->events()->getListeners('authenticate') as $adapter) {
            $adapter = $adapter->getCallback();
            if (is_array($adapter)) {
                $adapter = $adapter[0];
            }
            if ($adapter instanceof Adapter\AbstractAdapter) {
                $adapter->setSatisfied(false);
            }
        }
        return $this;
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
                gettype($adapter)
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
        if (null !== $this->resolvedIdentity) {
            return $this->resolvedIdentity;
        }

        $storage = $this->getStorage();
        if ($storage->isEmpty()) {
            return null;
        }

        $e = $this->getAuthEvent();
        $e->setIdentity($storage->read());

        $this->events()->trigger(__FUNCTION__ . '.resolve', $e);

        $this->resolvedIdentity = $e->getIdentity();

        return $this->resolvedIdentity;
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return AuthenticationService
     */
    public function clearIdentity()
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->clear();
        return $this;
    }

    /**
     * Attach the default listener to use for resolving the identity.
     * This mainly exists to allow attaching the listener via DI.
     * 
     * @param ListenerAggregate $identityResolver 
     * @return AuthenticationService
     */
    public function setDefaultIdentityResolver(ListenerAggregate $identityResolver)
    {
        $this->events()->attachAggregate($identityResolver);
        return $this;
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
