<?php

namespace ZfcUser\Factory\Form;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use ZfcUser\Form;
use ZfcUser\Validator;

class ChangeEmail implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null)
    {
        $options = $serviceManager->get('zfcuser_module_options');
        $form = new Form\ChangeEmail(null, $options);

        $form->setInputFilter(new Form\ChangeEmailFilter(
            $options,
            new Validator\NoRecordExists(array(
                'mapper' => $serviceManager->get('zfcuser_user_mapper'),
                'key'    => 'email'
            ))
        ));

        return $form;
    }
}
