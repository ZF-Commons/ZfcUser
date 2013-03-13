<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\EventManager\Event;
use Zend\Stdlib\RequestInterface as Request;

class AdapterChainEvent extends Event
{
    const AUTH_IDENTITY_PARAM = 'auth_identity';
    const AUTH_CREDENTIAL_PARAM = 'auth_credential';

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
        if (null === $identity) {
            // Setting the identity to null resets the code and messages.
            $this->setCode();
            $this->setMessages();
        }
        $this->setParam('identity', $identity);
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

    /**
     * Store the details for an authentication attempt.
     *
     * @param  strimg $identity
     * @param  string $credential
     * @return AdapterChainEvent
     */
    public function setAuthenticationParams($identity, $credential)
    {
        $this->setParam(self::AUTH_IDENTITY_PARAM, $identity);
        $this->setParam(self::AUTH_CREDENTIAL_PARAM, $credential);

        return $this;
    }

    /**
     * Get the authentication parameters.
     *
     * @return array
     */
    public function getAuthenticationParams()
    {
        return array(
            'identity'   => $this->getParam(self::AUTH_IDENTITY_PARAM),
            'credential' => $this->getParam(self::AUTH_CREDENTIAL_PARAM) 
        );
    }
}
