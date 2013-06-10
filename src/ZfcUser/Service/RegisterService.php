<?php

namespace ZfcUser\Service;

use ZfcUser\Entity\UserInterface;
use ZfcUser\Mapper\RegisterMapperInterface;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\Exception;
use Zend\Form\FormInterface;

class RegisterService
{
    /**
     * @var \Zend\Form\FormInterface
     */
    protected $form;

    /**
     * @var \ZfcUser\Mapper\RegisterMapperInterface
     */
    protected $mapper;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var UserInterface
     */
    protected $userPrototype;

    /**
     * @param FormInterface $form
     * @param RegisterMapperInterface $mapper
     * @param ModuleOptions $options
     */
    public function __construct(
        FormInterface $form,
        RegisterMapperInterface $mapper,
        ModuleOptions $options
    ) {
        $this->form    = $form;
        $this->mapper  = $mapper;
        $this->options = $options;
    }

    /**
     * Register a new user using the registration form and registration
     * mapper.
     *
     * @param array $data
     * @return null|UserInterface
     */
    public function register(array $data)
    {
        $this->form->bind(clone $this->getUserPrototype());
        $this->form->setData($data);

        if (!$this->form->isValid()) {
            return null;
        }

        $user = $this->form->getData();

        if (!$user instanceof UserInterface) {
            // todo: throw exception
            echo 'user is not right interface';
            exit;
        }

        $this->mapper->register($user);

        return $user;
    }

    /**
     * @return \Zend\Form\FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return UserInterface
     */
    public function getUserPrototype()
    {
        if (!$this->userPrototype) {
            $userClass = $this->options->getEntityClass();
            if (!class_exists($userClass)) {
                // todo: throw exception
                echo 'userclass ' . $userClass . ' could not be found';
                exit;
            }
            $this->userPrototype = new $userClass();
        }
        return $this->userPrototype;
    }
}