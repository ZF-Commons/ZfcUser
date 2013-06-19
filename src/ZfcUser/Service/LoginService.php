<?php

namespace ZfcUser\Service;

use ZfcUser\Authentication\AdapterChain;
use ZfcUser\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Form\FormInterface;
use ZfcUser\Plugin\LoginPluginInterface;

class LoginService extends AbstractPluginService
{
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
     * @var array
     */
    protected $allowedPluginInterfaces = array(
        'ZfcUser\Plugin\LoginPluginInterface'
    );

    /**
     * Logs a user in with the given identity and credential. Takes an array of parameters
     * which gets passed directly to the pre and post login events. It's up to each adapter
     * to ignore the auth attempt if the parameters they are expecting aren't available.
     *
     * @param array $data
     * @triggers LoginPluginInterface::EVENT_PRE_LOGIN
     * @triggers LoginPluginInterface::EVENT_POST_LOGIN
     * @return \Zend\Authentication\Result
     */
    public function login(array $data)
    {
        $this->getEventManager()->trigger('pre.login', $this, $data);

        $authService = $this->getAuthenticationService();
        $adapter     = $this->getAdapterChain();
        $adapter->setEventParams($data);

        $result = $authService->authenticate($adapter);

        $this->getEventManager()->trigger('post.login', $this, array('result' => $result));

        return $result;
    }

    /**
     * Clear authenticated identity.
     *
     * @triggers LoginPluginInterface::EVENT_PRE_LOGOUT
     * @triggers LoginPluginInterface::EVENT_POST_LOGOUT
     */
    public function logout()
    {
        $this->getEventManager()->trigger('pre.logout', $this);

        $this->getAuthenticationService()->clearIdentity();

        $this->getEventManager()->trigger('post.logout', $this);
    }

    /**
     * @param \ZfcUser\Authentication\AdapterChain $adapterChain
     * @return LoginService
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
     * @param \Zend\Authentication\AuthenticationService $authenticationService
     * @return LoginService
     */
    public function setAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        if (!$this->authenticationService instanceof AuthenticationService) {
            $this->setAuthenticationService(new AuthenticationService());
        }
        return $this->authenticationService;
    }

    /**
     * @throws Exception\UnexpectedValueException
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
     * @triggers LoginPluginInterface::EVENT_PREPARE_FORM
     * @return $this
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->getEventManager()->trigger(LoginPluginInterface::EVENT_PREPARE_FORM, $loginForm);
        $this->loginForm = $loginForm;
        return $this;
    }
}
