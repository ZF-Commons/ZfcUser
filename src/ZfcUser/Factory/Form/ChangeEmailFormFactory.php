<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\ChangeEmail;
use ZfcUser\Form\ChangeEmailFilter;
use ZfcUser\Validator\NoRecordExists;

class ChangeEmailFormFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $form = new ChangeEmail(null, $options);
        $form->setInputFilter(new ChangeEmailFilter(
            $options,
            new NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key'    => 'email'
            ))
        ));

        return $form;
    }
}
