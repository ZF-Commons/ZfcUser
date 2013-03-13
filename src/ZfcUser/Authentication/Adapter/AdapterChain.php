<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\EventManager\Event;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use ZfcBase\EventManager\EventProvider;

class AdapterChain extends EventProvider implements AdapterInterface
{
    /**
     * @var AdapterChainEvent
     */
    protected $event;

    /**
     * Returns the authentication result
     *
     * @return AuthenticationResult
     */
    public function authenticate()
    {
        $e = $this->getEvent();

        $result = new AuthenticationResult(
            $e->getCode(),
            $e->getIdentity(),
            $e->getMessages()
        );

        $this->resetAdapters();

        return $result;
    }

    /**
     * Prepares the adapter with the users credentials ready for an authentication attempt.
     *
     * @param  string $identity
     * @param  string $credential
     * @return boolean|Response
     */
    public function prepareForAuthentication($identity, $credential)
    {
        $e = $this->getEvent();

        $e->setAuthenticationParams($identity, $credential);

        $this->getEventManager()->trigger('authenticate.pre', $e);

        $result = $this->getEventManager()->trigger('authenticate', $e, function($test) {
            return ($test instanceof Response);
        });

        if ($result->stopped()) {
            if($result->last() instanceof Response) {
                return $result->last();
            } else {
                // throw new Exception('Auth event was stopped without a response.');
            }
        }

        if ($e->getIdentity()) {
            return true;
        }

        return false;
    }

    /**
     * resetAdapters
     *
     * @return AdapterChain
     */
    public function resetAdapters()
    {
        $listeners = $this->getEventManager()->getListeners('authenticate');
        foreach ($listeners as $listener) {
            $listener = $listener->getCallback();
            if (is_array($listener) && $listener[0] instanceof ChainableAdapter) {
                $listener[0]->getStorage()->clear();
            }
        }
        return $this;
    }

    /**
     * logoutAdapters
     *
     * @return AdapterChain
     */
    public function logoutAdapters()
    {
        //Adapters might need to perform additional cleanup after logout
        $this->getEventManager()->trigger('logout', $this->getEvent());
    }

    /**
     * Get the auth event
     *
     * @return AdapterChainEvent
     */
    public function getEvent()
    {
        if (null === $this->event) {
            $this->setEvent(new AdapterChainEvent);
            $this->event->setTarget($this);
        }
        return $this->event;
    }

    /**
     * Set an event to use during dispatch
     *
     * By default, will re-cast to AdapterChainEvent if another event type is provided.
     *
     * @param  Event $e
     * @return AdapterChain
     */
    public function setEvent(Event $e)
    {
        if ($e instanceof Event && !$e instanceof AdapterChainEvent) {
            $eventParams = $e->getParams();
            $e = new AdapterChainEvent();
            $e->setParams($eventParams);
            unset($eventParams);
        }
        $this->event = $e;
        return $this;
    }
}
