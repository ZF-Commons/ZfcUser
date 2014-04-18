<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\Form\ChangePassword;
use ZfcUser\Form\ChangePasswordFilter;

class ChangePasswordFormFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $form = new ChangePassword(null, $options);
        $form->setInputFilter(new ChangePasswordFilter($options));

        return $form;
    }
}
