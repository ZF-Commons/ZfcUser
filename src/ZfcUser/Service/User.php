<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationServiceInterface as AuthenticationService;
use Zend\Form\FormInterface as Form;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Mapper\HydratorInterface as Hydrator;
use ZfcUser\Mapper\UserInterface as UserMapper;
use ZfcUser\Options\UserServiceOptionsInterface as ServiceOptions;

class User extends EventProvider
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var ServiceOptions
     */
    protected $options;

    /**
     * @var Hydrator
     */
    protected $formHydrator;

    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Entity\UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function register(array $data)
    {
        $entityClass = $this->getOptions()->getUserEntityClass();
        $form        = $this->getRegisterForm();

        $form->setHydrator($this->getFormHydrator());
        $form->bind(new $entityClass);
        $form->setData($data);

        if ($form->isValid()) {
            $user   = $form->getData();
            $events = $this->getEventManager();

            $user->setPassword($this->getFormHydrator()->getCryptoService()->create($user->getPassword()));

            $events->trigger(__FUNCTION__, $this, compact('user', 'form'));
            $this->getUserMapper()->insert($user);
            $events->trigger(__FUNCTION__.'.post', $this, compact('user', 'form'));

            return $user;
        }
        return false;
    }

    /**
     * getUserMapper
     *
     * @return UserMapper
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            throw new \Exception('User mapper not injected!');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (null === $this->authService) {
            throw new \Exception('Auth service not injected!');
        }
        return $this->authService;
    }

    /**
     * setAuthenticationService
     *
     * @param AuthenticationService $authService
     * @return User
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * @return Form
     */
    public function getRegisterForm()
    {
        if (null === $this->registerForm) {
            throw new \Exception('Register form not injected!');
        }
        return $this->registerForm;
    }

    /**
     * @param Form $registerForm
     * @return User
     */
    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * get service options
     *
     * @return UserServiceOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof ServiceOptions) {
            throw new \Exception('Module options not injected!');
        }
        return $this->options;
    }

    /**
     * set service options
     *
     * @param ServiceOptions $options
     */
    public function setOptions(ServiceOptions $options)
    {
        $this->options = $options;
    }

    /**
     * Return the Form Hydrator
     *
     * @return Hydrator
     */
    public function getFormHydrator()
    {
        if (!$this->formHydrator instanceof Hydrator) {
            throw new \Exception('From hydrator not injected!');
        }

        return $this->formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param Hydrator $formHydrator
     * @return User
     */
    public function setFormHydrator(Hydrator $formHydrator)
    {
        $this->formHydrator = $formHydrator;
        return $this;
    }
}
