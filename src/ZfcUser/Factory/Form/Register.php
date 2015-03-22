<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 9:34 AM
 */

namespace ZfcUser\FormElementManagerFactory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form;
use ZfcUser\Validator;

class Register implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        $sm = $formElementManager->getServiceLocator();
        $options = $sm->get('zfcuser_module_options');
        $form = new Form\Register(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $form->getFormFactory()->setFormElementManager($formElementManager);

        //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
        $form->setHydrator($sm->get('zfcuser_register_form_hydrator'));
        $form->setInputFilter(new Form\RegisterFilter(
            new Validator\NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key'    => 'email'
            )),
            new Validator\NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key'    => 'username'
            )),
            $options
        ));
        return $form;
    }
}
