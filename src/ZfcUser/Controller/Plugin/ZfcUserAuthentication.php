<?php

namespace ZfcUser\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AdapterChain as AuthAdapter;

class ZfcUserAuthentication extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    /**
     * @var AuthAdapter
     */
    protected $authAdapter;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var PluginManager
     */
    protected $pluginManager;

    /**
     * Proxy convenience method
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * Proxy convenience method
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * Get authAdapter.
     *
     * @return ZfcUserAuthentication
     */
    public function getAuthAdapter()
    {
        if (null === $this->authAdapter) {
            $this->authAdapter = $this->getServiceLocator()->get('ZfcUser\Authentication\Adapter\AdapterChain');
        }
        return $this->authAdapter;
    }

    /**
     * Set authAdapter.
     *
     * @param authAdapter $authAdapter
     */
    public function setAuthAdapter(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
    }

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = $this->getServiceLocator()->get('zfcuser_auth_service');
        }
        return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->pluginManager->getServiceLocator();
    }

    /**
     * Set service manager instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        return $this;
    }
}
