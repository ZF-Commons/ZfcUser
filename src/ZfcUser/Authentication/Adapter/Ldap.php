<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Result as AuthenticationResult;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthEvent;
use ZfcUser\Options\AuthenticationOptionsInterface;

use Zend\Authentication\Adapter\Ldap as LdapAuthenticator;

class Ldap extends AbstractAdapter implements ServiceManagerAwareInterface, EventManagerAwareInterface
{
    /**
     * @var UserMapperInterface
     */
    protected $mapper;

    /**
     * @var closure / invokable object
     */
    protected $credentialPreprocessor;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var AuthenticationOptionsInterface
     */
    protected $options;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    public function authenticate(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
              ->setCode(AuthenticationResult::SUCCESS)
              ->setMessages(array('Authentication successful.'));
            return;
        }

        $identity   = $e->getRequest()->getPost()->get('identity');
        $credential = $e->getRequest()->getPost()->get('credential');
        $credential = $this->preProcessCredential($credential);
        $userObject = NULL;
        // For each one of the setvers configured try to find the 
        // USER in the LDAP dir
        /* @var $ldapOptions \ZfcUser\Options\LdapOptions */
        $ldapOptions = $this->getServiceManager()->get('zfcuser_ldap_options');
        /* @var $ldapAuthAdapter Zend\Authentication\Adapter\Ldap */
        $ldapAuthAdapter = new LdapAuthenticator($ldapOptions->getServers(),$identity,$credential);
        $result = $ldapAuthAdapter->authenticate();
        $mapper = $this->getMapper();
        switch ($result->getCode()) {
            case AuthenticationResult::SUCCESS:
                // Success!
                /* @var $ldapServer \Zend\Ldap\Ldap */
                $userObject = $mapper->findByUsername($identity);
                $ldapServer = $ldapAuthAdapter->getLdap();
                if($userObject === false) {
                    $entityClass = $this->getOptions()->getUserEntityClass();
                    /* @var $userObject \ZfcUser\Entity\UserInterface */
                    $userObject = new $entityClass;
                    $ldapEntry = $ldapServer->getEntry($ldapServer->getBoundUser());
                    // In the case that chainable auth is seted I should 
                    // store password in database :-|
                    //$bcrypt = new Bcrypt();
                    //$bcrypt->setCost($this->getOptions()->getPasswordCost());
                    /**
                     * @TODO:  Fetch config value for default user state
                     *         and set it here
                     */
                    $userObject->setDisplayName($ldapEntry['displayname'][0])
                               ->setUsername($identity)
                               //->setPassword($bcrypt->create($password));
                               ->setPassword('ldap');
                    //This just seems wrong, should I insert the user on my db?
                    //I'm just doing this so I can implement LDAP fast :-|
                    $this->insert($userObject,$ldapEntry);
                }
                $e->setIdentity($userObject->getId());
                $this->setSatisfied(true);
                $storage = $this->getStorage()->read();
                $storage['identity'] = $e->getIdentity();
                $this->getStorage()->write($storage);
                $e->setCode(AuthenticationResult::SUCCESS)
                  ->setMessages(array('Authentication successful.'));
                break;
            default:
                $e->setCode($result->getCode())
                    ->setMessages($result->getMessages());
                $this->setSatisfied(false);
                return false;
        }
    }

    public function preprocessCredential($credential)
    {
        $processor = $this->getCredentialPreprocessor();
        if (is_callable($processor)) {
            return $processor($credential);
        }
        return $credential;
    }

    /**
     * getMapper
     *
     * @return UserMapperInterface
     */
    public function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->mapper;
    }

    /**
     * setMapper
     *
     * @param UserMapperInterface $mapper
     * @return Db
     */
    public function setMapper(UserMapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * Get credentialPreprocessor.
     *
     * @return \callable
     */
    public function getCredentialPreprocessor()
    {
        return $this->credentialPreprocessor;
    }

    /**
     * Set credentialPreprocessor.
     *
     * @param $credentialPreprocessor the value to be set
     */
    public function setCredentialPreprocessor($credentialPreprocessor)
    {
        $this->credentialPreprocessor = $credentialPreprocessor;
        return $this;
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
     * @param AuthenticationOptionsInterface $options
     */
    public function setOptions(AuthenticationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * @return AuthenticationOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof AuthenticationOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('zfcuser_module_options'));
        }
        return $this->options;
    }
    
    /**
     * persists the user in the db, and trigger a pre and post events for it
     * @param  mixed  $user
     * @param  mixed  $userProfile
     * @return mixed
     */
    protected function insert($user, $ldapProfile)
    {
        $options = array(
            'user'          => $user,
            'ldapEntry'   => $ldapProfile,
        );

        $this->getEventManager()->trigger('registerViaLdap', $this, $options);
        $result = $this->getMapper()->insert($user);
        $this->getEventManager()->trigger('registerViaLdap.post', $this, $options);

        return $result;
    }
    
    /**
     * Set Event Manager
     *
     * @param  EventManagerInterface $events
     * @return HybridAuth
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;

        return $this;
    }

    /**
     * Get Event Manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }
}
