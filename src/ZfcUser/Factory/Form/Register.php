<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 9:34 AM
 */

namespace ZfcUser\Factory\Form;

use Zend\Form\FormElementManager;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form;
use ZfcUser\Options\RegistrationOptionsInterface;
use ZfcUser\Validator;

class Register implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        /** @var FormElementManager $formElementManager */
        $fem = $formElementManager;
        $sm = $formElementManager->getServiceLocator();
        /** @var RegistrationOptionsInterface $options */
        $options = $sm->get('zfcuser_module_options');
        $form = new Form\Register(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $form->getFormFactory()->setFormElementManager($fem);

        //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
        /** @var HydratorInterface $hydrator */
        $hydrator = $sm->get('zfcuser_register_form_hydrator');
        $form->setHydrator($hydrator);
        $form->setInputFilter(new Form\RegisterFilter(
            new Validator\NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key' => 'email'
            )),
            new Validator\NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key' => 'username'
            )),
            $options
        ));

        return $form;
    }
}
