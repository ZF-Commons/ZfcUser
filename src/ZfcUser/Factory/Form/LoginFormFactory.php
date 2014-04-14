<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\LoginForm;

/**
 * Class LoginFormFactory
 * @package ZfcUser\Factory\Form
 */
class LoginFormFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoginForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $serviceLocator \Zend\Form\FormElementManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();

        $form = new LoginForm();
        $form->setInputFilter($serviceManager->get('InputFilterManager')->get('ZfcUser\InputFilter\LoginFilter'));

        return $form;
    }
}
