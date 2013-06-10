<?php

namespace ZfcUser\Form\Factory;

use ZfcUser\Form\RegisterForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return RegisterForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \ZfcUser\Options\ModuleOptions $options */
        $options  = $serviceLocator->get('ZfcUser\Options\ModuleOptions');
        $hydrator = $options->getRegisterHydrator();

        if (is_string($hydrator)) {
            if ($serviceLocator->has($hydrator)) {
                $hydrator = $serviceLocator->get($hydrator);
            } else {
                $hydrator = new $hydrator();
            }
        }

        $form = new RegisterForm();
        $form->setHydrator($hydrator);

        return $form;
    }
}