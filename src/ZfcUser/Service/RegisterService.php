<?php

namespace ZfcUser\Service;

use ZfcUser\Entity\UserInterface;
use ZfcUser\Form\RegisterForm;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\Exception;

class RegisterService extends AbstractPluginService
{
    /**
     * @var RegisterForm
     */
    protected $registerForm;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var UserInterface
     */
    protected $userPrototype;

    /**
     * @var array
     */
    protected $allowedPluginInterfaces = array(
        'ZfcUser\Plugin\RegisterPluginInterface'
    );

    /**
     * @param ModuleOptions $options
     */
    public function __construct(ModuleOptions $options)
    {
        $this->options = $options;
    }

    /**
     * Register a new user using the registration form and registration
     * mapper.
     *
     * @param array $data
     * @throws Exception\InvalidUserException
     * @return null|UserInterface
     */
    public function register(array $data)
    {
        $form = $this->getRegisterForm();

        $form->bind(clone $this->getUserPrototype());
        $form->setData($data);

        if (!$form->isValid()) {
            return null;
        }

        $user = $form->getData();

        if (!$user instanceof UserInterface) {
            throw new Exception\InvalidUserException(
                'user must be an instance of ZfcUser\Entity\UserInterface'
            );
        }

        $this->getEventManager()->trigger(__FUNCTION__, $user);

        return $user;
    }

    /**
     * @param \ZfcUser\Form\RegisterForm $registerForm
     * @return RegisterService
     */
    public function setRegisterForm(RegisterForm $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * @return \ZfcUser\Form\RegisterForm
     */
    public function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm(new RegisterForm());
        }
        return $this->registerForm;
    }

    /**
     * @throws Exception\InvalidUserException
     * @return UserInterface
     */
    public function getUserPrototype()
    {
        if (!$this->userPrototype) {
            $userClass = $this->options->getEntityClass();
            if (!class_exists($userClass)) {
                throw new Exception\InvalidUserException(
                    sprintf(
                        'class %s could not be found',
                        $userClass
                    )
                );
            }
            $this->userPrototype = new $userClass();
            if (!$this->userPrototype instanceof UserInterface) {
                throw new Exception\InvalidUserException(
                    'user must be an instance of ZfcUser\Entity\UserInterface'
                );
            }
        }
        return $this->userPrototype;
    }
}