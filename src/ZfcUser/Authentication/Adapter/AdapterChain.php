<?php

namespace ZfcUser\Authentication\Adapter;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result as AuthenticationResult;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Stdlib\RequestInterface as Request;
use Laminas\Stdlib\ResponseInterface as Response;
use ZfcUser\Exception;

class AdapterChain implements AdapterInterface
{
    use EventManagerAwareTrait;

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
     * prepareForAuthentication
     *
     * @param  Request $request
     * @return Response|bool
     * @throws Exception\AuthenticationEventException
     */
    public function prepareForAuthentication(Request $request)
    {
        $e = $this->getEvent();
        $e->setRequest($request);

        $e->setName('authenticate.pre');
        $this->getEventManager()->triggerEvent($e);

        $e->setName('authenticate');
        $result = $this->getEventManager()->triggerEventUntil(function ($test) {
            return ($test instanceof Response);
        }, $e);

        if ($result->stopped()) {
            if ($result->last() instanceof Response) {
                return $result->last();
            }

            throw new Exception\AuthenticationEventException(
                sprintf(
                    'Auth event was stopped without a response. Got "%s" instead',
                    is_object($result->last()) ? get_class($result->last()) : gettype($result->last())
                )
            );
        }

        if ($e->getIdentity()) {
            $e->setName('authenticate.success');
            $this->getEventManager()->triggerEvent($e);
            return true;
        }

        $e->setName('authenticate.fail');
        $this->getEventManager()->triggerEvent($e);

        return false;
    }

    /**
     * resetAdapters
     *
     * @return AdapterChain
     */
    public function resetAdapters()
    {
        $sharedManager = $this->getEventManager()->getSharedManager();

        if ($sharedManager) {
            $listeners = $sharedManager->getListeners(['authenticate'], 'authenticate');
            foreach ($listeners as $listener) {
                if (is_array($listener) && $listener[0] instanceof ChainableAdapter) {
                    $listener[0]->getStorage()->clear();
                }
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
        $e = $this->getEvent();
        $e->setName('logout');
        $this->getEventManager()->triggerEvent($e);
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
        if (!$e instanceof AdapterChainEvent) {
            $eventParams = $e->getParams();
            $e = new AdapterChainEvent();
            $e->setParams($eventParams);
        }

        $this->event = $e;

        return $this;
    }
}
