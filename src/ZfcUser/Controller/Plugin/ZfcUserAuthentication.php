<?php

namespace ZfcUser\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManager;
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
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    
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
            $this->authAdapter = $this->getServiceManager()->get('ZfcUser\Authentication\Adapter\AdapterChain');
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
            $this->authService = $this->getServiceManager()->get('zfcuser_auth_service');
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
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->setServiceManager($serviceLocator->getServiceLocator());
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $locator
     * @return void
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
    
   /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->setServiceManager($serviceLocator->getServiceLocator());
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }
    
}
