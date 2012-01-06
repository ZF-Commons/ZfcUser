<?php

namespace EdpUser\Authentication\Adapter;

use Zend\Authentication\Adapter,
    Zend\Authentication\Result as AuthenticationResult,
    Zend\EventManager\Event,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response,
    EdpCommon\EventManager\EventProvider;

class AdapterChain extends EventProvider implements Adapter
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

        return new AuthenticationResult(
            $e->getCode(),
            $e->getIdentity(),
            $e->getMessages()
        );
    }

    public function prepareForAuthentication(Request $request)
    {
        $e = $this->getEvent();
        $e->setRequest($request);

        $result = $this->events()->trigger('authenticate', $e, function($test) {
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
     * Attach a chainable adapter 
     * 
     * @param ChainableAdapter $defaultAdapter 
     * @return AdapterChain
     */
    public function setDefaultAdapter(ChainableAdapter $defaultAdapter)
    {
        $defaultAdapter->getStorage()->clear();
        $this->events()->attach('authenticate', array($defaultAdapter, 'authenticate'));
        return $this;
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
