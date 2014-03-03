<?php
namespace ZfcUser\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\Form\Register;
use ZfcUser\Form\RegisterFilter;
use ZfcUser\Validator\NoRecordExists;

class RegisterFormFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $form = new Register(null, $options);
        //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
        $form->setInputFilter(new RegisterFilter(
            new NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key'    => 'email'
            )),
            new NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key'    => 'username'
            )),
            $options
        ));

        return $form;        
    }    
}
