<?php

namespace ZfcUser\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginFactory implements FactoryInterface
{
    /**
     * Creates an instance of the Login form.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Login
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');

        $form = new Login(null, $options);
        $form->setInputFilter(new LoginFilter($options));

        return $form;
    }
}
