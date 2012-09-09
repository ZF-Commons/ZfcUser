<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Result as AuthenticationResult;
use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthEvent;

class Cookie extends AbstractAdapter implements ServiceManagerAwareInterface
{
    protected $userMapper;

    protected $rememberMeMapper;

    protected $serviceManager;

    public function authenticate(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
                ->setCode(AuthenticationResult::SUCCESS)
                ->setMessages(array('Authentication successful.'));
            return;
        }

        $cookie = explode("\n", $_COOKIE['remember_me']);

        $rememberMe = $this->getRememberMeMapper()->findByEmailSerie($cookie[0], $cookie[1]);

        if(!$rememberMe)
            return false;

        if($rememberMe->getToken() !== $cookie[2])
        {
            // H4x0r
            // @TODO: Inform user of theft, change password?
            $this->getRememberMeMapper()->removeAll($cookie[0]);
            $this->setSatisfied(false);
            return false;
        }

        $userObject = $this->getUserMapper()->findByEmail($cookie[0]);

        $this->getRememberMeService()->updateSerie($rememberMe);

        // Success!
        $e->setIdentity($userObject->getId());
        $this->setSatisfied(true);
        $storage = $this->getStorage()->read();
        $storage['identity'] = $e->getIdentity();
        $this->getStorage()->write($storage);
        $e->setCode(AuthenticationResult::SUCCESS)
          ->setMessages(array('Authentication successful.'));
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

    public function setRememberMeMapper($rememberMeMapper)
    {
        $this->rememberMeMapper = $rememberMeMapper;
    }

    public function getRememberMeMapper()
    {
        if (null === $this->rememberMeMapper) {
            $this->rememberMeMapper = $this->getServiceManager()->get('zfcuser_rememberme_mapper');
        }
        return $this->rememberMeMapper;
    }

    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }
}