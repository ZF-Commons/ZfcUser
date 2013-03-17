<?php

namespace ZfcUser\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator;

class ChangeEmailFactory implements FactoryInterface
{
    /**
     * Creates an instance of the ChangeEmail form.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ChangeEmail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');

        $form = new ChangeEmail(null, $options);

        $form->setInputFilter(new ChangeEmailFilter(
            $options,
            new Validator\NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key'    => 'email'
            ))
        ));

        return $form;
    }
}
