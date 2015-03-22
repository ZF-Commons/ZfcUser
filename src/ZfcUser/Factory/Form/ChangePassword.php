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

class ChangePassword implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        $sm = $formElementManager->getServiceLocator();
        $options = $sm->get('zfcuser_module_options');
        $form = new Form\ChangePassword(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $formElementManager = $sm->get('FormElementManager');
        $form->getFormFactory()->setFormElementManager($formElementManager);

        $form->setInputFilter(new Form\ChangePasswordFilter($options));
        return $form;
    }
}
