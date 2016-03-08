<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 9:34 AM
 */

namespace ZfcUser\Factory\Form;

use Zend\Form\FormElementManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form;
use ZfcUser\Options\AuthenticationOptionsInterface;

class ChangePassword implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        /** @var FormElementManager $formElementManager */
        $fem = $formElementManager;
        $sm = $formElementManager->getServiceLocator();
        /** @var AuthenticationOptionsInterface $options */
        $options = $sm->get('zfcuser_module_options');
        $form = new Form\ChangePassword(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $form->getFormFactory()->setFormElementManager($fem);

        $form->setInputFilter(new Form\ChangePasswordFilter($options));

        return $form;
    }
}
