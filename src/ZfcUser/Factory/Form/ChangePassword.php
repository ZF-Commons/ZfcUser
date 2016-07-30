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

class ChangePassword implements FactoryInterface
{
    public function __invoke(ContainerInterface $formElementManager, $requestedName, array $options = null)
    {
        if ($formElementManager instanceof FormElementManager) {
            $sm = $formElementManager->getServiceLocator();
            $fem = $formElementManager;
        } else {
            $sm = $formElementManager;
            $fem = $sm->get('FormElementManager');
        }

        $options = $sm->get('zfcuser_module_options');
        $form = new Form\ChangePassword(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $form->getFormFactory()->setFormElementManager($fem);

        $form->setInputFilter(new Form\ChangePasswordFilter($options));

        return $form;
    }


    public function createService(ServiceLocatorInterface $formElementManager)
    {
        return $this->__invoke($formElementManager, null);
    }
}
