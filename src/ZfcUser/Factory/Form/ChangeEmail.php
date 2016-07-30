<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 9:34 AM
 */

namespace ZfcUser\Factory\Form;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Form\FormElementManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
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
