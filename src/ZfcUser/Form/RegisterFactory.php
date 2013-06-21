<?php

namespace ZfcUser\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator;

class RegisterFactory implements FactoryInterface
{
    /**
     * Creates an instance of the Register form.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Register
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');

        $form = new Register(null, $options);

        //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));

        $form->setInputFilter(new RegisterFilter(
            new Validator\NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key'    => 'email'
            )),
            new Validator\NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key'    => 'username'
            )),
            $options
        ));

        return $form;
    }
}
