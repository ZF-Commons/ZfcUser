<?php

namespace ZfcUser\Extension;

use Zend\Authentication\AuthenticationService;
use ZfcUser\Authentication\AdapterChain;
use ZfcUser\Form\LoginForm;

class Authentication extends AbstractExtension
{
    const EVENT_LOGIN_PRE          = 'authenticate.login.pre';
    const EVENT_LOGIN_POST         = 'authenticate.login.post';
    const EVENT_LOGIN_PREPARE_FORM = 'authenticate.login.prepareForm';
    const EVENT_LOGOUT_PRE         = 'authenticate.logout.pre';
    const EVENT_LOGOUT_POST        = 'authenticate.logout.post';

    /**
     * @var AdapterChain
     */
    protected $adapterChain;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * @var \Zend\Form\FormInterface
     */
    protected $loginForm;

    /**
     * @return string
     */
    public function getName()
    {
        return 'authentication';
    }

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Takes an array of parameters which gets passed directly to the pre and post login events.
     * It's up to each adapter to ignore the auth attempt if the parameters they are expecting aren't available.
     *
     * @param array $data
     * @triggers static::EVENT_LOGIN_PRE
     * @triggers static::EVENT_LOGIN_POST
     * @return \Zend\Authentication\Result
     */
    public function login(array $data)
    {
        $event = $this->getManager()->getEvent();
        $event->setParams($data);

        $this->getManager()->getEventManager()->trigger(static::EVENT_LOGIN_PRE, $event);

        $authService = $this->getAuthenticationService();
        $adapter     = $this->getAdapterChain();
        $adapter->setEventParams($data);

        $result = $authService->authenticate($adapter);

        $event->setParams(array('result' => $result));
        $this->getManager()->getEventManager()->trigger(static::EVENT_LOGIN_POST, $event);

        return $result;
    }

    /**
     * @param \ZfcUser\Authentication\AdapterChain $adapterChain
     * @return $this
     */
    public function setAdapterChain(AdapterChain $adapterChain)
    {
        $this->adapterChain = $adapterChain;
        return $this;
    }

    /**
     * @return \ZfcUser\Authentication\AdapterChain
     */
    public function getAdapterChain()
    {
        if (!$this->adapterChain instanceof AdapterChain) {
            $this->setAdapterChain(new AdapterChain());
        }
        return $this->adapterChain;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return \Zend\Form\FormInterface
     */
    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm(new LoginForm());
        }
        return $this->loginForm;
    }

    /**
     * @param LoginForm $loginForm
     * @triggers static::EVENT_LOGIN_PREPARE_FORM
     * @return $this
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->getManager()->getEventManager()->trigger(static::EVENT_LOGIN_PREPARE_FORM, $loginForm);
        $this->loginForm = $loginForm;
        return $this;
    }
}