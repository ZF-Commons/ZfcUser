<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 9:34 AM
 */

namespace ZfcUser\FormElementManagerFactory\Form;

use Zend\Form\FormElementManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form;

class Login implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        /** @var FormElementManager $formElementManager */
        $options = $formElementManager->getServiceLocator()->get('zfcuser_module_options');
        $form = new Form\Login(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $form->getFormFactory()->setFormElementManager($formElementManager);

        $form->setInputFilter(new Form\LoginFilter($options));
        return $form;
    }
}
