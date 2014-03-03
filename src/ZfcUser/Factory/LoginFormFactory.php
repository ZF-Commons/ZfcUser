<?php
namespace ZfcUser\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\Form\Login;
use ZfcUser\Form\LoginFilter;

class LoginFormFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $form = new Login(null, $options);
        $form->setInputFilter(new LoginFilter($options));

        return $form;        
    }    
}
