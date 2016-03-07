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
    protected $registerForm;

    /**
     * @var ServiceOptions
     */
    protected $options;

    /**
     * @var Hydrator
     */
    protected $formHydrator;

    public function __construct(
        UserMapper $userMapper,
        AuthenticationService $authService,
        Form $registerForm,
        ServiceOptions $options,
        Hydrator $formHydrator
    ) {
        $this->userMapper = $userMapper;
        $this->authService = $authService;
        $this->registerForm = $registerForm;
        $this->options = $options;
        $this->formHydrator = $formHydrator;
    }

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
        $form = $this->getRegisterForm();

        $form->setHydrator($this->getFormHydrator());
        $form->bind(new $entityClass);
        $form->setData($data);

        if ($form->isValid()) {
            $user = $form->getData();
            $events = $this->getEventManager();

            $user->setPassword($this->getFormHydrator()->getCryptoService()->create($user->getPassword()));

            $events->trigger(__FUNCTION__, $this, compact('user', 'form'));
            $this->getUserMapper()->insert($user);
            $events->trigger(__FUNCTION__ . '.post', $this, compact('user', 'form'));

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
        return $this->userMapper;
    }

    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @return Form
     */
    public function getRegisterForm()
    {
        return $this->registerForm;
    }

    /**
     * get service options
     *
     * @return UserServiceOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return the Form Hydrator
     *
     * @return Hydrator
     */
    public function getFormHydrator()
    {
        return $this->formHydrator;
    }
}
