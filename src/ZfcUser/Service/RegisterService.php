<?php

namespace ZfcUser\Service;

use Zend\Form\FormInterface;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\Exception;

class RegisterService extends AbstractPluginService
{
    /**
     * @var \Zend\Form\FormInterface
     */
    protected $form;

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
     * @param FormInterface $form
     * @param ModuleOptions $options
     */
    public function __construct(
        FormInterface $form,
        ModuleOptions $options
    ) {
        $this->form    = $form;
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
        $this->form->bind(clone $this->getUserPrototype());
        $this->form->setData($data);

        if (!$this->form->isValid()) {
            return null;
        }

        $user = $this->form->getData();

        if (!$user instanceof UserInterface) {
            throw new Exception\InvalidUserException(
                'User must be an instance of ZfcUser\Entity\UserInterface'
            );
        }

        return $this->getEventManager()->trigger(__FUNCTION__, $user)->last();
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