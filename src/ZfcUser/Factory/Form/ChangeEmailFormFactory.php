<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\ChangeEmail;
use ZfcUser\Form\ChangeEmailFilter;
use ZfcUser\Options;
use ZfcUser\Validator\NoRecordExists;

class ChangeEmailFormFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        /* @var $options Options\ModuleOptions */
        $options = $serviceManager->get('zfcuser_module_options');

        $userMapper = $serviceManager->get('zfcuser_user_mapper');

        $emailValidator = new NoRecordExists(array(
            'mapper' => $userMapper,
            'key' => 'email',
        ));

        $inputFilter = new ChangeEmailFilter(
            $options,
            $emailValidator
        );

        $form = new ChangeEmail(null, $options);
        $form->setInputFilter($inputFilter);

        return $form;
    }
}
