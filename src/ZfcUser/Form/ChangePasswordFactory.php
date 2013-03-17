<?php

namespace ZfcUser\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChangePasswordFactory implements FactoryInterface
{
    /**
     * Creates an instance of the ChangePassword form.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ChangePassword
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');

        $form = new ChangePassword(null, $options);
        $form->setInputFilter(new ChangePasswordFilter($options));

        return $form;
    }
}
